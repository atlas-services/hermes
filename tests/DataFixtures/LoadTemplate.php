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
        $templates = $configurations['template'];

        foreach ($templates as $i => $value) {

            $item = new Template();
            $item->setActive(true);
            $item->setType($value['type']);
            $item->setCode($value['code']);
            $item->setName($value['name']);
            $item->setSummary($value['summary']);

            $code = $value['code'];
            $this->addReference("$code", $item);

            $manager->persist($item);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['templates'];
    }

}
