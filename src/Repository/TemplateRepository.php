<?php

namespace App\Repository;

use App\Entity\Template;
use App\Repository\Traits\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

/**
 * @method Template|null find($id, $lockMode = null, $lockVersion = null)
 * @method Template|null findOneBy(array $criteria, array $orderBy = null)
 * @method Template[]    findAll()
 * @method Template[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Template::class);
    }


    public function getQbTemplates()
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.active = true ')
            ->orderBy('s.name', 'ASC')
        ;

        return $qb;
    }

    public function getTemplates()
    {
        $result = $this->getQbTemplates()
            ->getQuery()
            ->getResult()
        ;
        return $result;
    }
    public function getQbInitTemplates()
    {
        $qb = $this->getQbTemplates()
            ->where('s.code in (:libre) ' )
            ->setParameter('libre',  ['libre', 'folio1'] )
            ->orderBy('s.id', 'ASC')
        ;
        return $qb;
    }
    public function getTemplateLibre()
    {
        $result = $this->getQbTemplates()
            ->where('s.code = :libre ' )
            ->setParameter('libre',  'libre ' )
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result;
    }

}
