<?php
namespace Tests\Controller\Front;

use App\Menu\Page;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as testcase;
use Tests\DataFixtures\LoadFullMenu;

class PageTest extends TestCase
{

    const SITEMAP = [
        'xml' => 
        [
          0 => 
          [
            'name' => 'Sheet1',
            'sheetname' => 'Sheet1',
            'loc' => '/fr',
            'lastmod' => '2023-01-01',
            'changefreq' => 'weekly',
            'priority' => '0.5',
          ],
          1 => 
          [
            'name' => 'Sheet2Menu1',
            'sheetname' => 'Sheet2',
            'loc' => '/fr/sheet2/sheet2menu1',
            'lastmod' => '2023-01-01',
            'changefreq' => 'weekly',
            'priority' => '0.5',
          ],
          2 => 
          [
            'name' => 'Sheet2Menu2',
            'sheetname' => 'Sheet2',
            'loc' => '/fr/sheet2/sheet2menu2',
            'lastmod' => '2023-01-01',
            'changefreq' => 'weekly',
            'priority' => '0.5',
          ],
          3 => 
          [
            'name' => 'Sheet2Menu3',
            'sheetname' => 'Sheet2',
            'loc' => '/fr/sheet2/sheet2menu3',
            'lastmod' => '2023-01-01',
            'changefreq' => 'weekly',
            'priority' => '0.5',
          ],
          4 => 
          [
            'name' => 'Sheet2Menu4',
            'sheetname' => 'Sheet2',
            'loc' => '/fr/sheet2/sheet2menu4',
            'lastmod' => '2023-01-01',
            'changefreq' => 'weekly',
            'priority' => '0.5',
          ],
          5 => 
          [
            'name' => 'Sheet3Menu1',
            'sheetname' => 'Sheet3',
            'loc' => '/fr/sheet3/sheet3menu1',
            'lastmod' => '2023-01-01',
            'changefreq' => 'weekly',
            'priority' => '0.5',
          ],
          6 => 
          [
            'name' => 'Sheet3Menu2',
            'sheetname' => 'Sheet3',
            'loc' => '/fr/sheet3/sheet3menu2',
            'lastmod' => '2023-01-01',
            'changefreq' => 'weekly',
            'priority' => '0.5',
          ],
          7 => 
          [
            'name' => 'Sheet3Menu3',
            'sheetname' => 'Sheet3',
            'loc' => '/fr/sheet3/sheet3menu3',
            'lastmod' => '2023-01-01',
            'changefreq' => 'weekly',
            'priority' => '0.5',
          ],
          8 => 
          [
            'name' => 'Sheet3Menu4',
            'sheetname' => 'Sheet3',
            'loc' => '/fr/sheet3/sheet3menu4',
            'lastmod' => '2023-01-01',
            'changefreq' => 'weekly',
            'priority' => '0.5',
          ],
        ],
        'html' => 
        [
          'sheet1' => 
          [
            0 => 
            [
              'name' => 'Sheet1',
              'sheetname' => 'Sheet1',
              'loc' => '/fr',
              'lastmod' => '2023-01-01',
              'changefreq' => 'weekly',
              'priority' => '0.5',
            ],
          ],
          'sheet2' => 
          [
            0 => 
            [
              'name' => 'Sheet2Menu1',
              'sheetname' => 'Sheet2',
              'loc' => '/fr/sheet2/sheet2menu1',
              'lastmod' => '2023-01-01',
              'changefreq' => 'weekly',
              'priority' => '0.5',
            ],
            1 => 
            [
              'name' => 'Sheet2Menu2',
              'sheetname' => 'Sheet2',
              'loc' => '/fr/sheet2/sheet2menu2',
              'lastmod' => '2023-01-01',
              'changefreq' => 'weekly',
              'priority' => '0.5',
            ],
            2 => 
            [
              'name' => 'Sheet2Menu3',
              'sheetname' => 'Sheet2',
              'loc' => '/fr/sheet2/sheet2menu3',
              'lastmod' => '2023-01-01',
              'changefreq' => 'weekly',
              'priority' => '0.5',
            ],
            3 => 
            [
              'name' => 'Sheet2Menu4',
              'sheetname' => 'Sheet2',
              'loc' => '/fr/sheet2/sheet2menu4',
              'lastmod' => '2023-01-01',
              'changefreq' => 'weekly',
              'priority' => '0.5',
            ],
          ],
          'sheet3' => 
          [
            0 => 
            [
              'name' => 'Sheet3Menu1',
              'sheetname' => 'Sheet3',
              'loc' => '/fr/sheet3/sheet3menu1',
              'lastmod' => '2023-01-01',
              'changefreq' => 'weekly',
              'priority' => '0.5',
            ],
            1 => 
            [
              'name' => 'Sheet3Menu2',
              'sheetname' => 'Sheet3',
              'loc' => '/fr/sheet3/sheet3menu2',
              'lastmod' => '2023-01-01',
              'changefreq' => 'weekly',
              'priority' => '0.5',
            ],
            2 => 
            [
              'name' => 'Sheet3Menu3',
              'sheetname' => 'Sheet3',
              'loc' => '/fr/sheet3/sheet3menu3',
              'lastmod' => '2023-01-01',
              'changefreq' => 'weekly',
              'priority' => '0.5',
            ],
            3 => 
            [
              'name' => 'Sheet3Menu4',
              'sheetname' => 'Sheet3',
              'loc' => '/fr/sheet3/sheet3menu4',
              'lastmod' => '2023-01-01',
              'changefreq' => 'weekly',
              'priority' => '0.5',
            ],
          ],
        ],
    ]
      
  
    ;


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