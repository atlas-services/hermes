<?php

namespace App\Repository;

use App\Entity\Interfaces\ContactInterface;
use App\Entity\Sheet;
use App\Repository\Traits\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

/**
 * @method Sheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sheet[]    findAll()
 * @method Sheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SheetRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sheet::class);
    }

    /**
     * @return int Returns max position value
     */

    public function getMaxPosition()
    {
        $qb = $this->getQbMaxPosition();

        $list = $qb
            ->getQuery()
            ->getResult();

        if (isset($list[0])) {
            return ++$list[0]['position'];
        }
        if (isset($list['position'])) {
            return ++$list['position'];
        }
        return 1;
    }

    public function getQbSheets()
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.active = true ')
            ->orderBy('s.position', 'ASC')
        ;

        return $qb;
    }

    public function getQbSheetsWithoutContact()
    {
            $qb = $this->getQbSheets()
                ->where('s.code != :contact ')
                ->setParameter(ContactInterface::CONTACT, ContactInterface::CONTACT)
            ;
        return $qb;
    }


    public function getSheetsWithoutContact()
    {
        try {
            $list = $this->getQbSheetsWithoutContact()
                ->getQuery()
                ->getResult()
            ;
        }catch (\Exception $e){
            return [];
        }

        return $list;
    }

}
