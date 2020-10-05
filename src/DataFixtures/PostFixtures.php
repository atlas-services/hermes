<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Sheet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;
use Vich\UploaderBundle\Handler\DownloadHandler;

class PostFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $base = $_ENV['APP_BASE_FIXTURE'];
        if ("true" != $base) {
            $multi_users = $_ENV['APP_MULTI_USERS'];
            $menus = explode(',', $_ENV['APP_MENUS']);

            $menu = 'accueil';
            $template = 'section_libre';
            $item = new Post();

            if ($this->hasReference(UserFixtures::USER_ADMIN)) {
                $item->setUser($this->getReference(UserFixtures::USER_ADMIN));
            }
            $item->setActive(true);
            if ($this->hasReference("section$menu")) {
                $item->setSection($this->getReference("section$menu"));
            }
            $item->setName("$menu Accueil");
            $item->setContent("Contenu concernant le Post $menu $template");

            $manager->persist($item);


            foreach ($menus as $s => $menu) {
                $item = new Post();
                if ("true" == $multi_users) {
                    if ($this->hasReference(UserFixtures::USER_ADMIN_POST . $menu)) {
                        $item->setUser($this->getReference(UserFixtures::USER_ADMIN_POST . $menu));
                    }
                } else {
                    if ($this->hasReference(UserFixtures::USER_ADMIN)) {
                        $item->setUser($this->getReference(UserFixtures::USER_ADMIN));
                    }
                }
                $item->setActive(true);
                if ($this->hasReference("section$menu")) {
                    $item->setSection($this->getReference("section$menu"));
                }
                $item->setName("Post $s  de $menu");
                $item->setContent("Contenu concernant le Post $menu ");

                $manager->persist($item);
            }
            $manager->flush();
        }
    }

    public static function getGroups(): array
    {
        return ['posts'];
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            SheetFixtures::class,
            SectionFixtures::class,
        );
    }

}
