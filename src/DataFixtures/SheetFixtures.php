<?php

namespace App\DataFixtures;

use App\Entity\Sheet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;
use Vich\UploaderBundle\Handler\DownloadHandler;

class SheetFixtures extends Fixture  implements FixtureGroupInterface, DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $pages = explode(',', $_ENV['APP_PAGES']);
        foreach ( $pages as $key => $value){
            if(0 == $key){
                $n = 1;
            }else{
                $n = 10*$key;
            }
            $item = new Sheet();
            $item->setActive(true);
            $item->setCode($value);
            if('contact' == $value){
                $n = 99999 ;
                $item->setSlug($value);
            }
            $item->setPosition($n);
            $item->setName($value);
            $item->setSummary("Description sommaire concernant la page: $value");

            $this->addReference($value, $item);
            $manager->persist($item);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['sheets'];
    }


    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }

}
