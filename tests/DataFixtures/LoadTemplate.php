<?php

namespace Tests\DataFixtures;

use App\Entity\Hermes\Template;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadTemplate extends Fixture  implements FixtureGroupInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        $configurations = $this->container->getParameter('init');
        $templates = array_keys($configurations['template']);
//        dd($template_yaml);
//        $templates = explode(',', $_ENV['APP_TEMPLATES']);

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
