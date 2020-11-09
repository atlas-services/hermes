<?php

namespace App\Repository;

use App\Entity\Config;
use App\Repository\Traits\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

/**
 * @method Config|null find($id, $lockMode = null, $lockVersion = null)
 * @method Config|null findOneBy(array $criteria, array $orderBy = null)
 * @method Config[]    findAll()
 * @method Config[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Config::class);
    }

    public function getQbConfigByTypeOrderByCode($type)
    {
        return $this->createQueryBuilder('c')
            ->where('c.type = :type')
            ->setParameter('type', $type)
            ->orderBy('c.code', 'ASC')
//            ->addOrderBy('c.position', 'ASC')
            ;
    }
    public function getConfigByTypeOrderByCode($type)
    {
        return $this->getQbConfigByTypeOrderByCode($type)
            ->getQuery()
            ->getResult()
            ;
    }
}
