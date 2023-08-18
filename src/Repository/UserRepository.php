<?php

namespace App\Repository;

use App\Entity\Hermes\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOneByEmail($email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    public function findNewsletterUsers($role = "ROLE_NEWSLETTER", $all = true): ?array
    {
        $newsletter_users = [];
        
        $users =  $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult()
        ;

        foreach($users as $user){
            if($all == true OR $user->isActiveNewsletter()){
                if( [$role, "ROLE_USER"] == $user->getRoles() || [$role] == $user->getRoles()){
                    $newsletter_users[] = $user;
                }
            }
        }

        return $newsletter_users;
    }



    public function findNewsletterEmails($role = "ROLE_NEWSLETTER"): ?array
    {
        $emails = [];
        $newsletter_users = $this->findNewsletterUsers($role, false);
        
        foreach($newsletter_users as $user){
            $emails[] = $user->getEmail();
        }

        return $emails;
    }

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
