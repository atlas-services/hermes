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


    public function findNewsletterUsers($role = "ROLE_NEWSLETTER", $all = true, $active_newsletter = true): ?array
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
                    if(!$active_newsletter){
                        $user->setActiveNewsletter($active_newsletter);
                        $query = $this->createQueryBuilder('u')
                        ->update()
                        ->set('u.active_newsletter', ':active_newsletter')
                        ->where('u.id = :id')
                        ->setParameter('id', $user->getId())
                        ->setParameter('active_newsletter', $active_newsletter)
                        ->getQuery();
                        $query->execute();
                    }
                }
            }
        }

        return $newsletter_users;
    }



    public function findNewsletterEmails($role = "ROLE_NEWSLETTER", $active_newsletter = false): ?array
    {
        $emails = [];
        $newsletter_users = $this->findNewsletterUsers($role, false, $active_newsletter);
        
        foreach($newsletter_users as $user){
            $emails[] = $user->getEmail();
        }

        return $emails;
    }

 
    public function switchActive(int $id)
    {

        $user = $this->findOneById($id);

        if($user->isActiveNewsletter()){
            $user->setActiveNewsletter(false);
        }else{
            $user->setActiveNewsletter(true);
        }
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;

    }

}
