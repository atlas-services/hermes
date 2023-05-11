<?php

namespace App\EventListener;

use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\KernelEvent;

class MyRequestListener {
    private $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;

    }

    public function __invoke(KernelEvent $event): void
    {
        $redirectHome = ['/'];
        $request = $event->getRequest();
        $attributes = $request->attributes;



        $attr_locale = $attributes->get('_locale');
        $attr_sheet = $attributes->get('sheet');
        $attr_slug = $attributes->get('slug');
        $attr_route = $attributes->get('_route');
        switch ($attr_route) {
            case 'sheet' :
                $menu = $this->menuRepository->getMyMenuBySheetAndMenuSlugs($attr_sheet, $attr_slug, $attr_locale);
                if(is_null($menu)){
                    $redirectHome[] = $request->getPathInfo();
                }
                break;
            case 'slug' :
                $menu = $this->menuRepository->getMyMenuBySheetAndMenuSlugs($attr_slug, $attr_slug, $attr_locale);
                if(is_null($menu)){
                    $redirectHome[] = $request->getPathInfo();
                }

                break;
            case null :
            // case 'homepage_base':
                $redirectHome[] = $request->getPathInfo();
           }

        // redirect vers "Home menu ou First menu"
        if (in_array($request->getPathInfo(), $redirectHome)){
            $locale = $request->getLocale();
            $locales = $this->menuRepository->findBy(['locale' =>$locale]);
            if(empty($locales)){
                $locale = $request->getDefaultLocale();
            }

            $intl = \IntlCalendar::getAvailableLocales();
            if( !in_array($locale, $intl)){
                $locale = $request->getDefaultLocale();
            }
            $home_menu= $this->menuRepository->getHomeMenu($locale);
            $menu_slug = $home_menu->getSlug();
            $sheet_slug = $home_menu->getSheet()->getSlug();
            if($menu_slug == $sheet_slug){
                $url = sprintf("/%s/%s", $locale, $sheet_slug);
            }else{
                $url = sprintf("/%s/%s/%s", $locale, $sheet_slug, $menu_slug);
            }
        
            $redirect = new RedirectResponse($url);
            $event->setResponse($redirect);

        }
    }

}

