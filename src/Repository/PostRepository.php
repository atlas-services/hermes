<?php

namespace App\Repository;

use App\Entity\Hermes\Section;
use App\Entity\Hermes\Post;
use App\Repository\Traits\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;
use phpDocumentor\Reflection\Types\Collection;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return int Returns max position value
     */

    public function getMaxPosition(Section $section = null)
    {
        $qb = $this->getQbMaxPosition();
        if(isset($section)) {
            $qb
            ->where('m.section = :section')
            ->setParameter(':section', $section->getId());
        }
        $list = $qb
            ->getQuery()
            ->getResult()
        ;
        if(isset($list[0])){
            return ++$list[0]['position'];
        }
        if(isset($list['position'])){
            return ++$list['position'];
        }
        return 1;
    }

    /**
     * @return int Returns max position value
     */

    public function switchActive(int $id)
    {

        $post = $this->findOneById($id);

        if($post->isActive()){
            $post->setActive(false);
        }else{
            $post->setActive(true);
        }
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();

        return $post;

    }


    /**
     * @return int Returns max position value
     */
     public function getEditablePosts()
     {
 
        $allposts = $this->findAll();
        foreach($allposts as $post){
            if(!is_null($post->getSection())){
                $posts[] = $post;
            }
        }

         return $posts;
 
     }
//    /**
//     * @return Collection
//     */
//
//    public function findAllWithSearch(?string $term)
//    {
//
//        $qb = $this->createQueryBuilder('p');
//        if ($term) {
//            $qb->andWhere('p.content LIKE :term ')
//                ->setParameter('term', '%' . htmlentities($term) . '%')
//            ;
//        }
//        return $qb
//            ->andWhere('p.active = 1 ')
//            ->getQuery()
//            ->getResult()
//            ;
//    }

}
