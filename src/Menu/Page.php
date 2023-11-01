<?php


namespace App\Menu;

use App\Entity\Config\Config;
use App\Entity\Hermes\Contact;
use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Sheet;
use App\Entity\Interfaces\ContactInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Page
{

    protected $doctrine;
    protected $parameterBag;
    protected $config;
    public function __construct(ManagerRegistry $doctrine, ParameterBagInterface $parameterBag)
    {
        $this->doctrine = $doctrine;
        $this->parameterBag = $parameterBag;     
        $emConfig = $doctrine->getManager('config');
        $this->config = $emConfig->getRepository(Config::class, 'config')->getActiveConfig(); 
    }

    public function getActiveMenu($sheet, $slug, $route = null, $locale='fr')
    {
        $locale = $this->getLocale($locale);
        $em = $this->doctrine->getManager('default');
          /*
         * On récupère la liste des pages, la liste des menus et le menu sélectionné .
         */
        $sheet_actives = $em->getRepository(Sheet::class)->findBy(['active' => true, 'locale' => $locale], ['position' => 'ASC']);
        $menus = $em->getRepository(Menu::class)->getMenusByLocale($locale);
        /*
         * @TODO simplification config
         */
//        if (isset($config['forms'])) {
//            $menus = $this->getActiveForm($menus, $config['forms'], $locale);
//        }

        $menu = $em->getRepository(Menu::class)->getMyMenuBySheetAndMenuSlugs($sheet, $slug, $locale);
        $hasContact = false;
        $hasNewsletter = false;
        $listForms = [];
        if(!is_null(($menu))){
            $sectionsMenu = $menu->getSections();
            foreach ($sectionsMenu as $section){
                if( ContactInterface::TYPE  === $section->getTemplate()->getType()){
                    $hasContact = true;
                    $listForms[] = $section->getTemplate()->getCode();
                    if( ContactInterface::NEWSLETTER  === $section->getTemplate()->getCode()){
                        $hasNewsletter = true;
                    }
                }
            }
        }

        foreach ($sheet_actives as $menu_active) {
            $sheets[$menu_active->getName()] = $menu_active;
            $sheetsSlug[$menu_active->getName()] = $menu_active->getSlug();
        }
        $locales =$em->getRepository(Menu::class)->getLocalesByMenu($menu, $sheet);

        $nav = $this->getNavBarByLocaleAndSlug($locale, $slug );     

        if(!empty($nav)){
            $key_menu_home = array_keys($nav)[0];
            if(is_array($nav[$key_menu_home])){
                $key_sous_menu_home = array_keys($nav[$key_menu_home])[0];
                $home_menu = $nav[$key_menu_home][$key_sous_menu_home];
                $home_menu_slug = $home_menu['slug'];
                $home_sheet_slug = $home_menu['sheet'];
            }else{
                $home_menu_slug = $nav[$key_menu_home];
                $home_sheet_slug = $nav[$key_menu_home];
            }
            $home = ['sheet' => $home_sheet_slug, 'slug' => $home_menu_slug];
        }else{
            $home =  ['sheet' => "/", 'slug' => null];
        }

        $contact_subjects = [
            Contact::CONTACT => Contact::CONTACT ,
        ];

        if(($this->config['newsletter_active'])){
            $contact_subjects[Contact::NEWSLETTER_TEXTE]  = Contact::NEWSLETTER ;
        }
        
        if(($this->config['livredor_active'])){
            $contact_subjects[Contact::LIVREDOR_TEXTE]  = Contact::LIVREDOR ;
        }

        $array = [
//            'config' => $config,
            'sheets' => $sheet_actives ?? [],
            'sheetsSlug' => $sheetsSlug ?? [],
            'menus' => $menus ?? $sheets,
            'menu' => $menu,
            'hasContact' => $hasContact,
            'hasNewsletter' => $hasNewsletter,
            'listForms' => $listForms,
            'locales' => $locales,
            'nav' => $nav,
            'home' => $home,
            'contact_subjects' => $contact_subjects,
        ];

        /*
         * @TODO simplification config
         */
        $array = array_merge($array, $this->config);

        return $array;

    }

    public function getNavBarByLocaleAndSlug($locale, $slug)
    {
        $em = $this->doctrine->getManager('default');
        $menus = [];
        $navbar = [];
        $menuSlug = $em->getRepository(Menu::class)
            ->findOneBy(['active' => true ,'locale' => $locale, 'slug'=>$slug]);

        if(!is_null($menuSlug)){
            $referenceName = $menuSlug->getReferenceName();
            $menuName = $em->getRepository(Menu::class)
                ->findOneBy(['active' => true, 'locale' => $locale, 'referenceName'=>$referenceName])->getName();
        }

        $menusLocale = $em->getRepository(Menu::class)
            ->getMenusByLocaleOrderByPosition($locale)
        ;

        foreach ($menusLocale as $menu){
            if($locale == $menu->getSheet()->getLocale()){
                $menus[$menu->getSheet()->getName()][$menu->getName()] = [
                    'sheet' =>  $menu->getSheet()->getSlug(),
                    'slug' =>  $menu->getSlug(),
                ]
                ;
            }
        }
        foreach ($menus as $sheet_name => $listmenu) {
            $nav['active'] = '';
            $nav['dropdown'] = '';
            $nav['dropdowntoggle'] = '';
            $nav['color_link'] = $this->config['nav_link_color'];
            $nav['bg_color_link'] = 'transparent';

            if ((isset($referenceName) && $referenceName == $sheet_name) or (isset($menuName) && in_array($menuName, array_keys($listmenu)) )) {
                $nav['active'] = 'active';
                $nav['color_link'] = $this->config['nav_color_active'];
                $nav['bg_color_link'] = $this->config['nav_bgcolor_active'];
            }
            $navbar[$sheet_name] = $listmenu;
            if (is_array($listmenu)) {
                // if ($sheet_name == array_key_first($menus) && (1 == count($menus[array_key_first($menus)])) ) {
                if ($sheet_name == array_key_first($menus[array_key_first($menus)]) && (1 == count($menus[array_key_first($menus)])) ) {
                    $nav['href'] = sprintf("/%s/", $locale);                
                }else{
                    if ((strtolower($sheet_name) != strtolower(array_key_first($listmenu))) or 2 < count($listmenu)) {
                        $nav['href'] = '#';
                        $nav['dropdown'] = 'dropdown';
                        $nav['dropdowntoggle'] = 'page-scroll dropdown-toggle';
                    }else{
                    //    $nav['href'] = sprintf("/%s/%s", $locale, $listmenu[$sheet_name]['slug']); ;
                    //    $nav['href'] = sprintf("/%s/%s", $locale, strtolower($sheet_name)); 
                       $nav['href'] = sprintf("/%s/%s", $locale, $listmenu[$sheet_name]['slug']); 
                    }
                 }
                $navbar[$sheet_name] = array_merge($navbar[$sheet_name], $nav);
                $nav = [];
            };

        };

        return $navbar;
    }

    public function getSitemapByLocale($locale, $host="")
    {
        $em = $this->doctrine->getManager('default');
        $urls = $urls_xml = $urls_html = [];
        $menusLocale = $em->getRepository(Menu::class)
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
        $em = $this->doctrine->getManager('default');
        $menu = $em->getRepository(Menu::class)->getMyMenuBySheetAndMenuSlugs($sheet, $slug);
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

    public function getLocale($locale)
    {
        $em = $this->doctrine->getManager('default');

        $locales = $em->getRepository(Menu::class)->findBy(['locale' =>$locale]);

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