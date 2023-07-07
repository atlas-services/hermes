<?php

namespace Tests\DataFixtures;

use App\Entity\Hermes\Template;
use App\Entity\Hermes\Sheet;
use App\Entity\Hermes\Menu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadFullMenu extends Fixture  implements FixtureGroupInterface, ContainerAwareInterface
{
    const REF_SHEET = 'sheet';
    const REF_MENU = 'menu';
    const DATE_MENU = '2023-01-01';
    const SHEET_1 = 'Sheet1';
    const SHEET_2 = 'Sheet2';
    const SHEET_3 = 'Sheet3';
    const SHEETS = [
        self::SHEET_1,
        self::SHEET_2,
        self::SHEET_3,
    ];


    const MENU_1 = 'Sheet1';
    const MENU_21 = 'Sheet2Menu1';
    const MENU_22 = 'Sheet2Menu2';
    const MENU_23 = 'Sheet2Menu3';
    const MENU_24 = 'Sheet2Menu4';
    const MENU_31 = 'Sheet3Menu1';
    const MENU_32 = 'Sheet3Menu2';
    const MENU_33 = 'Sheet3Menu3';
    const MENU_34 = 'Sheet3Menu4';

    const MENUS_SHEETS = [
        self::MENU_1 => self::SHEET_1,
        self::MENU_21 => self::SHEET_2,
        self::MENU_22 => self::SHEET_2,
        self::MENU_23 => self::SHEET_2,
        self::MENU_24 => self::SHEET_2,
        self::MENU_31 => self::SHEET_3,
        self::MENU_32 => self::SHEET_3,
        self::MENU_33 => self::SHEET_3,
        self::MENU_34 => self::SHEET_3,
    ];



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

        $this->loadSheets($manager);
        $this->loadMenus($manager);

    }


    public function loadSheets($manager)
    {

        $sheets = self::SHEETS;

        $pos = 1;

        foreach ($sheets as $key => $value) {

            $item = new Sheet();
            $item->setActive(true);
            $item->setLocale('fr');
            $item->setReferenceName($sheet->getName())($value));
            $item->setCode($value);
            $item->setName($value);
            $item->setSlug(strtolower($value));
            $item->setSummary($value);
            $item->setPosition($pos);

            $code = $value;
            $this->addReference(self::REF_SHEET.$code, $item);

            $manager->persist($item);
            $pos++;
        }
        
        $manager->flush();
    }
    public function loadMenus($manager)
    {

        $menus = self::MENUS_SHEETS;

        $pos = 1;

        foreach ($menus as $key => $value) {

            $sheet = $this->getReference(self::REF_SHEET.$value);
            $item = new Menu();
            $item->setActive(true);
            $item->setSheet($sheet);
            $item->setLocale('fr');
            $item->setReferenceName($key);
            $item->setCode($key);
            $item->setName($key);
            $item->setSlug(strtolower($key));
            $item->setPosition($pos);
            $item->setUpdatedAt(new \DateTime(self::DATE_MENU));

            $code = $key;
            $this->addReference(self::REF_MENU.$code, $item);

            $manager->persist($item);
            $pos++;
        }
        
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['menus'];
    }

}
