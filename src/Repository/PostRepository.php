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
        $allposts = $this->createQueryBuilder('s')
        ->orderBy('s.section', 'ASC')
        ->addOrderBy('s.position', 'ASC')
        ->addOrderBy('s.active', 'DESC')
        ->getQuery()
        ->getResult();
        // $allposts = $this->findAll();
        foreach($allposts as $key => $post){
            if(!is_null($post->getSection())){
                if(!is_null($post->getSection()->getMenu())){
                // $posts[] = $post;
                    $posts[$key]['id'] = $post->getId();
                    $posts[$key]['active'] = $post->isActive();
                    $posts[$key]['position'] = $post->getPosition();
                    $posts[$key]['name'] = $post->getName();
                    $posts[$key]['startPublishedAt'] = $post->getStartPublishedAt();
                    $posts[$key]['endPublishedAt'] = $post->getEndPublishedAt();
                    $posts[$key]['updatedAt'] = $post->getUpdatedAt();
                    $posts[$key]['section'] = $post->getSection()->getName();
                    $posts[$key]['section_id'] = $post->getSection()->getId();
                    $posts[$key]['menu'] = $post->getSection()->getMenu()->getName();
                    $posts[$key]['template'] = $post->getSection()->getTemplate()->getName();
                    $posts[$key]['template_code'] = $post->getSection()->getTemplate()->getCode();
                    $posts[$key]['menu_id'] = $post->getSection()->getMenu()->getId();
                    $posts[$key]['menu_slug'] = $post->getSection()->getMenu()->getSlug();
                    $posts[$key]['locale'] = $post->getSection()->getMenu()->getLocale();
                    $posts[$key]['sheet'] = $post->getSection()->getMenu()->getSheet()->getName();
                }
            }
        }

         return array_values($posts);

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
