<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture  implements FixtureGroupInterface
{
    public const USER_SUPER_ADMIN = 'user-super-admin';
    public const USER_ADMIN = 'user-admin';
    public const USER_ADMIN_POST = 'user-admin-post';
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        //super admin
        $user = new User();
        $user->setFirstname('Tayeb');
        $user->setLastname('CHIKHI');
        $user->setEmail('hermes@atlas-services.fr');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setPassword('atlasatlas');
        $this->addReference(self::USER_SUPER_ADMIN, $user);
        $manager->persist($user);

//        //admin
//        $user = new User();
//        $user->setFirstname('Patrick');
//        $user->setLastname('Dupond');
//        $user->setEmail('admin@yopmail.fr');
//        $user->setRoles(['ROLE_ADMIN']);
//        $user->setPassword('atlasatlas');
//        $this->addReference(self::USER_ADMIN, $user);
//        $manager->persist($user);

        //admin-post
//        $menus = explode(',', $_ENV['APP_MENUS']);

//        foreach ($menus as $s => $menu) {
//            $user = new User();
//            $user->setFirstname("Pierre");
//            $user->setLastname($menu);
//            $email = strtolower("$menu@yopmail.com");
//            $user->setEmail($email);
//            $user->setRoles(['ROLE_ADMIN_POST']);
//            $user->setPassword('atlasatlas');
//
//            $this->addReference(self::USER_ADMIN_POST.$menu, $user);
//
//            $manager->persist($user);
//        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['users'];
    }

}
