<?php


namespace App\Menu;

use App\Entity\Config\Config;
use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Sheet;
use App\Entity\Interfaces\ContactInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Page
{
    protected $entityManager;
    protected $parameterBag;
    protected $config;

    public function __construct(ManagerRegistry $doctrine, ParameterBagInterface $parameterBag)
    {
        $this->em = $doctrine->getManager('default');
        $this->emConfig = $doctrine->getManager('config');
        $this->parameterBag = $parameterBag;
        $this->config = $this->emConfig->getRepository(Config::class, 'config')->getActiveConfig();
       
    }

    public function getActiveMenu($sheet, $slug, $route = null, $locale='fr')
    {
        $locale = $this->getLocale($locale);

        /*
         * On récupère la liste des pages, la liste des menus et le menu sélectionné .
         */
        $sheet_actives = $this->em->getRepository(Sheet::class)->findBy(['active' => true, 'locale' => $locale], ['position' => 'ASC']);
        $menus = $this->em->getRepository(Menu::class)->getMenusByLocale($locale);
        /*
         * @TODO simplification config
         */
//        if (isset($config['forms'])) {
//            $menus = $this->getActiveForm($menus, $config['forms'], $locale);
//        }

        $menu = $this->em->getRepository(Menu::class)->getMyMenuBySheetAndMenuSlugs($sheet, $slug, $locale);
        $hasContact = false;
        if(!is_null(($menu))){
            $sectionsMenu = $menu->getSections();
            foreach ($sectionsMenu as $section){
                if( ContactInterface::CONTACT  == $section->getTemplate()->getCode()){
                    $hasContact = true;
                }
            }
        }

        foreach ($sheet_actives as $menu_active) {
            $sheets[$menu_active->getName()] = $menu_active;
            $sheetsSlug[$menu_active->getName()] = $menu_active->getSlug();
        }
        $locales =$this->em->getRepository(Menu::class)->getLocalesByMenu($menu, $sheet);

        $nav = $this->getNavBarByLocaleAndSlug($locale, $slug );       
        if(!empty($nav)){
            $key_menu_home = array_keys($nav)[0];
            if(is_array($nav[$key_menu_home])){
                $key_sous_menu_home = array_keys($nav[$key_menu_home])[0];
                $home_menu = $nav[$key_menu_home][$key_sous_menu_home];
                $home_menu_slug = $home_menu->getSlug();
                $home_sheet_slug = $home_menu->getSheet()->getSlug();
            }else{
                $home_menu_slug = $nav[$key_menu_home];
                $home_sheet_slug = $nav[$key_menu_home];
            }
            $home = ['sheet' => $home_sheet_slug, 'slug' => $home_menu_slug];
        }else{
            $home =  ['sheet' => "/", 'slug' => null];
        }

        $array = [
//            'config' => $config,
            'sheets' => $sheet_actives ?? [],
            'sheetsSlug' => $sheetsSlug ?? [],
            'menus' => $menus ?? $sheets,
            'menu' => $menu,
            'hasContact' => $hasContact,
            'locales' => $locales,
            'nav' => $nav,
            'home' => $home,
        ];

        /*
         * @TODO simplification config
         */
        $array = array_merge($array, $this->config);

        return $array;

    }

    public function getNavBarByLocaleAndSlug($locale, $slug)
    {
        $menus = [];
        $navbar = [];
        $menuSlug = $this->em->getRepository(Menu::class)
            ->findOneBy(['active' => true ,'locale' => $locale, 'slug'=>$slug]);

        if(is_null($menuSlug)){
            $referenceName = "home";
            $localeName = "home";
        }else{
            $referenceName = $menuSlug->getReferenceName();
            $localeName = $this->em->getRepository(Menu::class)
                ->findOneBy(['active' => true, 'locale' => $locale, 'referenceName'=>$referenceName])->getName();
        }

        $menusLocale = $this->em->getRepository(Menu::class)
            ->getMenusByLocaleOrderByPosition($locale)
        ;

        foreach ($menusLocale as $menu){
            if($locale == $menu->getSheet()->getLocale()){
                $menus[$menu->getSheet()->getName()][$menu->getName()] = $menu;
            }
        }
        foreach ($menus as $sheet_name => $listmenu) {
            $nav['active'] = '';
            $nav['dropdown'] = '';
            $nav['dropdowntoggle'] = '';
            $nav['border_bottom'] = '';

            if ($referenceName == $sheet_name or in_array($localeName, array_keys($listmenu))) {
                $nav['active'] = 'active';
            }
            $navbar[$sheet_name] = $listmenu;
            if (is_array($listmenu)) {
                if ((strtolower($sheet_name) != strtolower(array_key_first($listmenu))) or 2 < count($listmenu)) {
                    $nav['href'] = '#';
                    $nav['dropdown'] = 'dropdown';
                    $nav['dropdowntoggle'] = 'page-scroll dropdown-toggle';
                    $nav['border_bottom'] = 'border-bottom';
                }
                $navbar[$sheet_name]['config'] = $nav;
                $nav = [];
            };

        };

        return $navbar;
    }



    public function getSitemapByLocale($locale, $host="")
    {
        $urls = $urls_xml = $urls_html = [];
        $menusLocale = $this->em->getRepository(Menu::class)
            ->getMenusByLocaleOrderByPosition($locale)
        ;
        foreach ($menusLocale as $key => $menu){
            $updated = $menu->getUpdatedAt();
            if(is_null($updated)){
                $updated = (new \DateTime("now"))->format('Y-m-d');
            }else{
                $updated = $menu->getUpdatedAt()->format('Y-m-d');
            }
            if($locale == $menu->getSheet()->getLocale()){
                $name = $menu->getName();
                $sheet_name = $menu->getSheet()->getName();
                if($menu->getSheet()->getSlug() == $menu->getSlug()){
                    $name = $menu->getSheet()->getName();
                    $loc = $host. '/'. $locale. '/'.$menu->getSheet()->getSlug();
                }else{
                    $loc = $host. '/'. $locale. '/'.$menu->getSheet()->getSlug(). '/' . $menu->getSlug();                
                }
                // homepage
                if(0 == $key){
                    $loc = $host. '/'. $locale;                
                }
                $urls_xml[] = [
                    'name' => $name,
                    'sheetname' => $sheet_name,
                    'loc' => $loc,
                    'lastmod' => $updated,
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ];
                $urls_html[$menu->getSheet()->getSlug()][] = [
                    'name' => $name,
                    'sheetname' => $sheet_name,
                    'loc' => $loc,
                    'lastmod' => $updated,
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ];
            }
        }
        $urls['xml'] = $urls_xml;
        $urls['html'] = $urls_html;
        return $urls;
    }

    public function getCacheMenu($sheet, $slug)
    {
        $menu = $this->em->getRepository(Menu::class)->getMyMenuBySheetAndMenuSlugs($sheet, $slug);
//        // cache remote pictures
        $cache = [];
        if(!is_null($menu)){
//            // cache menu
//            if(!is_null($menu->getSheet()->getUpdatedAt())) {
//                $cache['front_cache'] = 'front_cache_sheet' . $sheet . $slug . $menu->getSheet()->getUpdatedAt()->format('Y-m-d-H-i-s');
//            }
//            // cache sous-menu
//            if(!is_null($menu->getUpdatedAt())) {
//                $cache['front_cache'] = 'front_cache_menu' . $sheet . $slug . $menu->getUpdatedAt()->format('Y-m-d-H-i-s');
//            }
//            //cache section
            foreach ($menu->getSections() as $section){
//                // cache Posts
//                foreach ($section->getPosts() as $post){
//                    if(!is_null($post->getUpdatedAt())) {
//                        $cache['front_cache'] = 'front_cache_post' . $sheet . $slug . $post->getUpdatedAt()->format('Y-m-d-H-i-s');
//                    }
//                }
            }
        }

        return $cache;
    }

    public function getLocale($locale){

        $locales = $this->em->getRepository(Menu::class)->findBy(['locale' =>$locale]);

        if(empty($locales)){
            $locale = $this->parameterBag->get('app.default_locale');
            return $locale;
        }
        $intl = \IntlCalendar::getAvailableLocales();

        if( !in_array($locale, $intl)){
            $locale = $this->parameterBag->get('app.default_locale');
        }
        return $locale;
    }

}