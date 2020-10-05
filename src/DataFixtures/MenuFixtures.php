<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;
use Vich\UploaderBundle\Handler\DownloadHandler;

class MenuFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private $downloadHandler;

    public function __construct(DownloadHandler $downloadHandler)
    {
        $this->downloadHandler = $downloadHandler;
    }

    public function load(ObjectManager $manager)
    {
        $base = $_ENV['APP_BASE_FIXTURE'];

        // Menu accueil si page accueil existe
        if ($this->hasReference('accueil')) {
            $item = new Menu();
            $value = 'accueil';
            if ($this->hasReference(UserFixtures::USER_ADMIN)) {
                $item->setUser($this->getReference(UserFixtures::USER_ADMIN));
            }
            $item->setActive(true);
            if ($this->hasReference($value)) {
                $item->setSheet($this->getReference($value));
            }
            if ($this->hasReference("section$value")) {
                $item->addSection($this->getReference("section$value"));
            }
            $item->setName($value);
            $item->setSlug($this->slugify($value));
//        $item->setSummary("Texte sommaire concernant le Menu $value");

//        $item->setFileName(strtolower($value . '.jpg'));
//        $this->downloadHandler->downloadObject($item, $fileField = 'imageFile');

            $this->addReference("menu$value", $item);

            $manager->persist($item);

            $manager->flush();
        }
        if ("true" != $base) {
            $multi_users = $_ENV['APP_MULTI_USERS'];
            $menus = explode(',', $_ENV['APP_MENUS']);

            // Menus configurés
            foreach ($menus as $i => $value) {
                $item = new Menu();
                if ("true" == $multi_users) {

                    if ($this->hasReference(UserFixtures::USER_ADMIN_POST . $value)) {
                        $item->setUser($this->getReference(UserFixtures::USER_ADMIN_POST . $value));
                    }
                } else {
                    if (true == $this->hasReference(UserFixtures::USER_ADMIN)) {
                        $item->setUser($this->getReference(UserFixtures::USER_ADMIN));
                    }
                }
                if ($this->hasReference("section$value")) {
                    $item->addSection($this->getReference("section$value"));
                }
                $item->setActive(true);
                if ($this->hasReference($value)) {
                    $item->setSheet($this->getReference($value));
                }
                $item->setName("Menu $value");
                $item->setSlug($this->slugify($value));
                $item->setSummary("Texte sommaire concernant le Menu $value $i");

//            $item->setFileName(strtolower($value . '.jpg'));
//            $this->downloadHandler->downloadObject($item, $fileField = 'imageFile');

                $this->addReference("menu$value", $item);

                $manager->persist($item);
            }

            $manager->flush();
        }
    }

    public
    static function getGroups(): array
    {
        return ['posts'];
    }

    public
    function getDependencies()
    {
        return array(
            UserFixtures::class,
            SheetFixtures::class,
            SectionFixtures::class,
        );
    }

    protected function slugify($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

}
