<?php
namespace Tests\Controller\Front;

use App\Menu\Page;
use Tests\Traits\ConstTrait;
use Tests\DataFixtures\LoadFullMenu;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as testcase;


class PageTest extends TestCase
{
  use ConstTrait;

    // public function testGetActiveMenu()
    // {
        
    //     self::bootKernel();
    //     $container = static::getContainer();
    //     $page = $container->get(Page::class);

    //     $active_menu = $page->getActiveMenu( strtolower(LoadFullMenu::SHEET_1), strtolower(LoadFullMenu::MENU_1, 'sheet', 'fr'));

    //     dd($active_menu);
    //    $this->assertEquals(self::SITEMAP, $active_menu);

    // }
    // public function testGetNavBarByLocaleAndSlug()
    // {
        
    //     self::bootKernel();
    //     $container = static::getContainer();
    //     $page = $container->get(Page::class);

    //     $navbar = $page->getNavBarByLocaleAndSlug('fr', strtolower(LoadFullMenu::SHEET_1));

    //     dd($navbar);
    //    $this->assertEquals(self::SITEMAP, $navbar);

    // }

    public function testGetSitemapByLocale()
    {
        
        self::bootKernel();
        $container = static::getContainer();
        $page = $container->get(Page::class);

        $sitemap = $page->getSitemapByLocale('fr');

       $this->assertEquals(self::SITEMAP, $sitemap);

    }

}