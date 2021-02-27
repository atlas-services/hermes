<?php

namespace App\Repository;

use App\Entity\Hermes\Temoignage;
use App\Repository\Traits\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

/**
 * @method Temoignage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Temoignage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Temoignage[]    findAll()
 * @method Temoignage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemoignageRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Temoignage::class);
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

    /**
     * @return int Returns max position value
     */

    public function switchActive(int $id)
    {

        $temoignage = $this->findOneById($id);

        if($temoignage->isActive()){
            $temoignage->setActive(false);
        }else{
            $temoignage->setActive(true);
        }
        $this->getEntityManager()->persist($temoignage);
        $this->getEntityManager()->flush();

        return $temoignage;

    }

}
