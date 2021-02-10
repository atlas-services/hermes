<?php

namespace App\Repository;

use App\Entity\Block;
use App\Repository\Traits\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

/**
 * @method Block|null find($id, $lockMode = null, $lockVersion = null)
 * @method Block|null findOneBy(array $criteria, array $orderBy = null)
 * @method Block[]    findAll()
 * @method Block[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Block::class);
    }

    public function getBlocks(){
        $blocks = $this->createQueryBuilder('s')
            ->where('s.active = 1')
            ->andWhere('s.name LIKE :name')
            ->setParameter('name', 'block%')
            ->getQuery()
            ->getResult()
        ;

        $aBlocks =[];
        if(count($blocks) > 0){
            foreach($blocks as $block){
                foreach($block->getBlockPosts() as $post){
                    $aBlocks[$block->getName()][] = $post;
                }
            }
        }
        return $aBlocks;
    }

}
