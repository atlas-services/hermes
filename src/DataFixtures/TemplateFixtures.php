<?php

namespace App\DataFixtures;


use App\Entity\Template;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;

class TemplateFixtures extends Fixture implements FixtureGroupInterface
{


    public function load(ObjectManager $manager)
    {

        $templates = explode(',', $_ENV['APP_TEMPLATES']);

        foreach ($templates as $i => $value) {

            $item = new Template();
            $item->setActive(true);
            $item->setCode($value);
            $item->setName($value);
            $item->setSummary("Texte sommaire concernant le Template $value");

            $this->addReference("$value", $item);

            $manager->persist($item);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['templates'];
    }

}
