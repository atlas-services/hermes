<?php

namespace Tests\DataFixtures;

use App\Entity\Hermes\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class LoadUser extends Fixture  implements FixtureGroupInterface
{
    const USER_SUPER_ADMIN = 'user-super-admin';
    const USER_ADMIN = 'user-admin';
    const USER_ADMIN_POST = 'user-admin-post';
    const EMAIL = 'test@atlas-services.fr';
    const PASSWORD = 'pwtest';

    public function load(ObjectManager $manager)
    {
        //super admin
        $user = new User();
        $user->setFirstname('Tayeb');
        $user->setLastname('CHIKHI');
        $user->setEmail(self::EMAIL);
        $user->setPassword(self::PASSWORD);
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $this->addReference(self::USER_SUPER_ADMIN, $user);
        $manager->persist($user);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['users'];
    }

}
