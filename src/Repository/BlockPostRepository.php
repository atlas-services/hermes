<?php

namespace App\Repository;

use App\Entity\BlockPost;
use App\Repository\Traits\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

/**
 * @method BlockPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlockPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlockPost[]    findAll()
 * @method BlockPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockPostRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BlockPost::class);
    }

}
