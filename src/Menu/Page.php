<?php


namespace App\Menu;


use App\Entity\Config\Config;
use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Section;
use App\Entity\Hermes\Sheet;
use App\Entity\Hermes\Temoignage;
use Doctrine\ORM\EntityManagerInterface;

class Page
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getActiveMenu($configuration,$sheet, $slug, $route = null)
    {
        /*
         * On récupère la configuration du site.
         */
        $config = $this->getActiveConfig($configuration);
//        dd($config);

        /*
         * On récupère la liste des pages, la liste des menus et le menu sélectionné .
         */
        $sheet_actives = $this->entityManager->getRepository(Sheet::class)->findBy(['active' => true], ['position' => 'ASC']);

        $menus = $this->entityManager->getRepository(Menu::class)->getMenus();
        // Ajouter les formulaires configurés
//        if (isset($config['form'])) {
//            $menus = $this->getActiveForm($menus, ($config['form'])->getValue());
//        }
        /*
         * @TODO simplification config
         */
        if (isset($config['forms'])) {
            $menus = $this->getActiveForm($menus, $config['forms']);
        }

        $menu = $this->entityManager->getRepository(Menu::class)->getMyMenuBySheetAndMenuSlugs($sheet, $slug);

        foreach ($sheet_actives as $menu_active) {
            $sheets[$menu_active->getName()] = $menu_active;
            $sheetsSlug[$menu_active->getName()] = $menu_active->getSlug();
        }

        $array = [
//            'config' => $config,
            'sheets' => $sheet_actives ?? [],
            'sheetsSlug' => $sheetsSlug ?? [],
            'menus' => $menus ?? $sheets,
            'menu' => $menu,
        ];
        /*
         * @TODO simplification config
         */
        $array = array_merge($array, $config);

        $nav = $this->getInfoMenus($menus, $sheetsSlug ?? [], $sheet, $route);
        $array['nav'] = $nav;

        return $array;

    }

    public function getActiveForm($menus, $listform)
    {
        if (is_null($listform)) {
            return $menus;
        }
        $forms = explode(',', $listform);

        foreach ($forms as $form) {
            if (!array_key_exists(strtoupper($form), $menus)) {
                $sheet_form = $this->entityManager->getRepository(Sheet::class)->findOneBy(['active' => true, 'name' => $form]);
                if (!is_null($sheet_form)) {
                    $menus = array_merge($menus, [$form => $sheet_form]);
                }
            }
        }
        $this->entityManager->flush();

        return $menus;
    }

    public function getActiveConfig($configuration)
    {
        /*
         * On récupère la configuration du site.
         */
         foreach ($configuration as $conf) {
            $config[$conf->getCode()] = $conf;
            if('bg_image' != $conf->getCode() && 'favicon' != $conf->getCode() && 'accueil' != $conf->getCode() && 'logo' != $conf->getCode()){
                $config_simple[$conf->getCode()] = $conf->getValue();
            }else{
                $config_simple[$conf->getCode()] = $conf;
            }
        }

        return $config_simple ;
        return $config;
    }

    public function getInfoMenus($menus, $sheetsSlug, $sheet, $route)
    {
        /*
         * On défini la configuration du menu.
         */
        $nav = [];
        $newMenus = [];
        foreach ($menus as $sheet_name => $listmenu) {
            $nav['active'] = '';
            $nav['dropdown'] = '';
            $nav['dropdowntoggle'] = '';
            $nav['border_bottom'] = '';
            $sheet_slug = $sheetsSlug[$sheet_name];
            if ($sheet_slug == $sheet or $sheet_slug == $route) {
                $nav['active'] = 'active';
            }
            $newMenus[$sheet_name] = $listmenu;
            if (is_array($listmenu)) {
                if ((strtolower($sheet_name) != strtolower(array_key_first($listmenu))) or 2 < count($listmenu)) {
                    $nav['href'] = '#';
                    $nav['dropdown'] = 'dropdown';
                    $nav['dropdowntoggle'] = 'page-scroll dropdown-toggle';
                    $nav['border_bottom'] = 'border-bottom';
                }
                $newMenus[$sheet_name]['config'] = $nav;
                $nav = [];
            };

        };

        return $newMenus;
    }

    public function getCacheMenu($sheet, $slug)
    {
        $menu = $this->entityManager->getRepository(Menu::class)->getMyMenuBySheetAndMenuSlugs($sheet, $slug);
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
                if(!is_null($section->getRemote())){
                    $cache['front_cache'] =  'front_cache_remote_'.$sheet.$slug.$section->getRemote()->getUpdatedAt()->format('Y-m-d-H-i-s');
                }
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

}