<?php

namespace App\DataFixtures;

use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use App\Entity\Hermes\Sheet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;
use Vich\UploaderBundle\Handler\DownloadHandler;

class SectionFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private $downloadHandler;

    public function __construct(DownloadHandler $downloadHandler)
    {
        $this->downloadHandler = $downloadHandler;
    }

    public function load(ObjectManager $manager)
    {
        $base = $_ENV['APP_BASE_FIXTURE'];
        if ("true" != $base) {
            $multi_users = $_ENV['APP_MULTI_USERS'];
            $menus = explode(',', $_ENV['APP_MENUS']);
            $templates = explode(',', $_ENV['APP_TEMPLATES']);

            // Sections accueil
            $menu = 'accueil';
            $template = 'section_libre';
            $item = new Section();
            $item->setActive(true);
            $item->setName("section $menu");
            if ($this->hasReference($template)) {
                $item->setTemplate($this->getReference("$template"));
            }
            if ($this->hasReference(UserFixtures::USER_ADMIN)) {
                $item->setUser($this->getReference(UserFixtures::USER_ADMIN));
            }
            $this->addReference("section$menu", $item);
            $manager->persist($item);


            // Autres sections
            foreach ($menus as $s => $menu) {
//            foreach ($templates as $i => $template) {
                $item = new Section();
                $item->setActive(true);
                $item->setName("section $menu");
                if ('portfolio' == $menu) {
                    $template = "section_folio";
                } else {
                    $template = "section_catalogue";
                }
                if ($this->hasReference($template)) {
                    $item->setTemplate($this->getReference("$template"));
                }
//                if ($this->hasReference(UserFixtures::USER_ADMIN_POST . $menu)) {
//                    $item->setUser($this->getReference(UserFixtures::USER_ADMIN_POST . $menu));
//                }
                if ("true" == $multi_users) {
                    if ($this->hasReference(UserFixtures::USER_ADMIN_POST . $menu)) {
                        $item->setUser($this->getReference(UserFixtures::USER_ADMIN_POST . $menu));
                    }
                } else {
                    if (true == $this->hasReference(UserFixtures::USER_ADMIN)) {
                        $item->setUser($this->getReference(UserFixtures::USER_ADMIN));
                    }
                }

                $this->addReference("section$menu", $item);
                $manager->persist($item);

//            }
            }
            $manager->flush();
        }
    }

    public static function getGroups(): array
    {
        return ['section'];
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            SheetFixtures::class,
        );
    }

}
