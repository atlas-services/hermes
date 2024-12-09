<?php

namespace App\Repository\Traits;

use App\Entity\AbstractContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Collection;

trait BaseRepositoryTrait
{


// /**
//  * @return  Returns max position value
//  */
    public function getQbMaxPosition($table = null)
    {
        return $this->createQueryBuilder('m')
            ->select('m.position')
            ->orderBy('m.position', 'DESC')
            ->andWhere('m.position != 99')
           ;
    }


    /**
     * @return $data
     */
    public function switchActive(int $id)
    {

        $data = $this->findOneById($id);

        if($data->isActive()){
            $data->setActive(false);
        }else{
            $data->setActive(true);
        }
        $this->getEntityManager()->persist($data);
        $this->getEntityManager()->flush();

        return $data;

    }


    /**
     * @return Boolean
     */
    public function switchPosition(int $id1, int $id2) : bool
    {
        try{
            $data1 = $this->findOneById($id1);  
            $data2 = $this->findOneById($id2);
 
            $position1 = $data1->getPosition();
            $position2 = $data2->getPosition();
    
            $data1->setPosition($position2);
            $data2->setPosition($position1);
    
            $this->getEntityManager()->persist($data1);
            $this->getEntityManager()->persist($data2);
            
            $this->getEntityManager()->flush();
    
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    /**
     * @return Collection
     */

    public function findAllWithSearch(?string $term)
    {

        $qb = $this->createQueryBuilder('p');
        if ($term) {
            $qb->andWhere('p.content LIKE :term ')
                ->setParameter('term', '%' . htmlentities($term) . '%')
            ;
        }
        return $qb
            ->andWhere('p.active = 1 ')
            ->getQuery()
            ->getResult()
            ;
    }

//     /**
//      * @return int Returns max position value
//      */
//
//    public function getMaxPosition($table = null)
//    {
//        $list = $this->createQueryBuilder('m')
//            ->select('m.position')
//            ->orderBy('m.position', 'DESC')
//            ->getQuery()
//            ->getResult()
//        ;
//        if(isset($list[0])){
//            return ++$list[0]['position'];
//        }
//        if(isset($list['position'])){
//            return ++$list['position'];
//        }
//        return 1;
//    }


    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
