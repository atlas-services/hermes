<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Front;

use App\Entity\Config\Config;
use App\Entity\Hermes\Block;
use App\Entity\Hermes\Contact;
use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Sheet;
use App\Entity\Interfaces\ContactInterface;
use App\Entity\Hermes\Section;
use App\Entity\Hermes\Temoignage;
use App\Form\ContactType;
use App\Form\Admin\TemoignageType;
use App\Mailer\Mailer;
use App\Menu\Page;
use App\Repository\PostRepository;
use App\Repository\TemoignageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Doctrine\ORM\EntityManagerInterface;

class FrontController extends AbstractController
{

    /**
    * @Route(
    *     "/{_locale}/sitemap.xml",
    *     name="sitemap",
    *     methods={"GET|POST"}
    *     )
    */
    public function sitemap(Request $request, Page $page)
    {
        $localeRouting = $request->attributes->get('_locale' , 'fr');
        $locale = $page->getLocale($localeRouting);
        $host = $request->getSchemeAndHttpHost();
        $urls = $page->getSitemapByLocale($locale, $host);

        $response = new Response(
            $this->renderView('front/base/hermes/sitemap/sitemapxml.html.twig', ['urls' => $urls['xml']]),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;


    }

    /**
     * @Route(
     *     "/{_locale}/search",
     *     name="search_content",
     *     methods={"GET|POST"}
     *     )
     */
    public function search(Request $request, PostRepository $postRepository, TemoignageRepository $temoignageRepository, RouterInterface $router, Page $page)
    {
        $locale = $request->attributes->get('_locale' , 'fr');
        $referer = $request->headers->get('referer');
        $refererPathInfo = Request::create($referer)->getPathInfo();
        $refererPathInfo = str_replace($request->getScriptName(), '', $refererPathInfo);
        $routeInfos = $router->match($refererPathInfo);
        $configuration = $this->getDoctrine()->getRepository(Config::class, 'config')->findBy(['active' => true]);
        if(ContactInterface::CONTACT == $routeInfos['_route'] || ContactInterface::LIVREDOR_ROUTE == $routeInfos['_route']){
            $route = $routeInfos['_route'];
            $array = $page->getActiveMenu($configuration, ContactInterface::LIVREDOR_TEXTE, ContactInterface::LIVREDOR_TEXTE, $route, $locale);
        }else{
            $sheet = $routeInfos['sheet'];
            $slug = $routeInfos['slug'];
            $array = $page->getActiveMenu($configuration, $sheet, $slug);
        }

        $q = $request->query->get('q');
        $posts = [
            'posts' => array_merge($postRepository->findAllWithSearch($q),$temoignageRepository->findAllWithSearch($q) ),
        ];

        $urls = $page->getSitemapByLocale($locale);
        $array['urls'] = $urls['html'];
        $array = array_merge($array, $posts);

        return $this->render('front/search_result.html.twig', $array);

    }


    /**
     * @Route(
     *     "/contacta",
     *     name="contact",
     *     methods={"GET|POST"}
     *     )
     * @Route(
     *     "/{_locale}/livre-d-or",
     *     name="livre-d-or",
     *     methods={"GET|POST"}
     *     )
     */
    public function form(Request $request, CacheInterface $backCache, Mailer $mailer, Page $page, $sheet = 'accueil', $slug = 'accueil')
    {
        $route = $request->attributes->get('_route');
        $locale = $request->attributes->get('_locale' , 'fr');
        $configuration =$this->getDoctrine()->getRepository(Config::class, 'config')->findBy(['active' => true]);
        if (ContactInterface::LIVREDOR_ROUTE == $route) {
            $array = $page->getActiveMenu($configuration, ContactInterface::LIVREDOR_TEXTE, ContactInterface::LIVREDOR_TEXTE, $route, $locale);
            $entity = new Temoignage();
            $form = $this->createForm(TemoignageType::class, $entity,
                array(
                    'action' => $this->generateUrl(ContactInterface::LIVREDOR_ROUTE),
                    'method' => 'POST',
                ));
            $entityManager = $this->getDoctrine()->getManager();
            $livredor = $entityManager->getRepository(Temoignage::class)->findBy(['active' => true]);
            $array[ContactInterface::LIVREDOR] = $livredor;
        }
        if (ContactInterface::CONTACT == $route) {
            $array = $page->getActiveMenu($configuration, ContactInterface::CONTACT, ContactInterface::CONTACT,$route, $locale);
            $entity = new Contact();
            $form = $this->createForm(ContactType::class, $entity,
                array(
                    'action' => $this->generateUrl('contact'),
                    'method' => 'POST',
                ));
        }

        // On vérifie qu'elle est de type « POST ».
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if (ContactInterface::LIVREDOR_ROUTE == $route) {
                    $entityManager->persist($form->getData());
                    $entityManager->flush();
                }

                // On récupère notre objet.
                $entity = $form->getData();
                $context = array_merge(['contact_form'=>$entity], $array );
                $template = 'front/contact/_includes/email.html.twig';
                $return = $mailer->send($entity, $array['contact'], 'Contact', $template, $context);
                $this->addFlash($return['type'], $return['message']);
                $notification = $return['message'];
                $this->addFlash('info', $notification);
                return $this->redirect('/');
//                return $this->redirectToRoute($route);
            } else {
                $notification = "Votre message n'a pas été envoyé.";
                $this->addFlash('error', $notification);
            }
            $array['notification'] = $notification;
        }
        $array['form'] = $form->createView();

        return $this->render('front/index.html.twig', $array);
    }

    /**
     *  @Route(
     *     "/",
     *     name="home",
     *     methods={"GET|POST"}
     *     )
     * @Route(
     *     "/{_locale?}",
     *     name="homepage",
     *     methods={"GET|POST"}
     *     )
     */
    public function homepage(Request $request, CacheInterface $frontCache, Mailer $mailer, Page $page )
    {
        $route = $request->attributes->get('_route');
        $localeRouting = $request->attributes->get('_locale' , 'fr');
        $locale = $page->getLocale($localeRouting);
        $home_menu = $this->getDoctrine()->getRepository(Menu::class)->getHomeMenu($locale);
        $sheet = $home_menu->getSheet()->getSlug();
        $slug = $home_menu->getSlug();
        if ('livre-d-or' == $sheet) {
            return $this->redirectToRoute('livre-d-or');
        }
        $array = $this->getArray($page, $sheet, $slug, $route, $locale);
        $localeNotExists = !in_array($localeRouting, array_keys($array['locales']));
        if(is_null($array['menu']) or $localeNotExists){
            $home_sheet = $array['home']['sheet'];
            $home_slug = $array['home']['slug'];
            // return $this->redirectToRoute('sheet', [ '_locale'=> $locale, 'sheet'=> $home_sheet, 'slug' => $home_slug]);
        }
 
        if($array['hasContact']){
            $array = $this->baseForm($request, $array, $mailer, $route);
            if(!is_array($array)){
                return $this->redirect($array);
            }
            return $this->render('front/index.html.twig', $array);
        }
        return $this->render('front/index.html.twig', $array);
    }

    /**
     * @Route(
     *     "/{_locale}/{slug}",
     *     name="slug",
     *     methods={"GET|POST"}
     *     )
     */
    public function page(Request $request, CacheInterface $frontCache, Mailer $mailer, Page $page, $slug )
    {
        $sheet = $slug;
        $route = $request->attributes->get('_route');
        $localeRouting = $request->attributes->get('_locale' , 'fr');
        $locale = $page->getLocale($localeRouting);
        if ('livre-d-or' == $sheet) {
            return $this->redirectToRoute('livre-d-or');
        }
        $array = $this->getArray($page, $sheet, $slug, $route, $locale);
        $localeNotExists = !in_array($localeRouting, array_keys($array['locales']));
        if(is_null($array['menu']) or $localeNotExists){
            $home_sheet = $array['home']['sheet'];
            $home_slug = $array['home']['slug'];
            // return $this->redirectToRoute('sheet', [ '_locale'=> $locale, 'sheet'=> $home_sheet, 'slug' => $home_slug]);
        }

        if($array['hasContact']){
            $array = $this->baseForm($request, $array, $mailer, $route);
            if(!is_array($array)){
                return $this->redirect($array);
            }
            return $this->render('front/index.html.twig', $array);
        }
        return $this->render('front/index.html.twig', $array);
    }

    /**
     * @Route(
     *     "/{_locale}/{sheet}/{slug}",
     *     name="sheet",
     *     methods={"GET|POST"}
     *     )
     */
    public function pageSheet(Request $request, CacheInterface $frontCache, Mailer $mailer, Page $page, $sheet , $slug)
    {
        $route = $request->attributes->get('_route');
        $localeRouting = $request->attributes->get('_locale' , 'fr');
        $locale = $page->getLocale($localeRouting);
        if ('livre-d-or' == $sheet) {
            return $this->redirectToRoute('livre-d-or');
        }
        $array = $this->getArray($page,$sheet, $slug, $route, $locale);
        $localeNotExists = !in_array($localeRouting, array_keys($array['locales']));
        if(is_null($array['menu']) or $localeNotExists){
            $home_sheet = $array['home']['sheet'];
            $home_slug = $array['home']['slug'];
            return $this->redirectToRoute('sheet', [ '_locale'=> $locale, 'sheet'=> $home_sheet, 'slug' => $home_slug]);
        }

        if($array['hasContact']){
            $array = $this->baseForm($request, $array, $mailer, $route);
            if(!is_array($array)){
                return $this->redirect($array);
            }
            return $this->render('front/index.html.twig', $array);
        }
        return $this->render('front/index.html.twig', $array);
    }

    private function baseForm($request, $array, $mailer, $route){
            $entity = new Contact();
            $form = $this->createForm(ContactType::class, $entity,);

            // On vérifie qu'elle est de type « POST ».
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    if (ContactInterface::LIVREDOR_ROUTE == $route) {
                        $entityManager->persist($form->getData());
                        $entityManager->flush();
                    }

                    // On récupère notre objet.
                    $entity = $form->getData();
                    $context = array_merge(['contact_form'=>$entity], $array );
                    $template = 'front/contact/_includes/email.html.twig';
                    $return = $mailer->send($entity, $array['contact'], 'Contact', $template, $context);

                    $this->addFlash($return['type'], $return['message']);
                    $notification = $return['message'];
                    $this->addFlash('info', $notification);
                    $redirect = "/". $this->getLocale();
                    return $redirect;
//                return $this->redirectToRoute($route);
                } else {
                    $notification = "Votre message n'a pas été envoyé.";
                    $this->addFlash('error', $notification);
                }
                $array['notification'] = $notification;
             }
            $array['form'] = $form->createView();
            return $array;

    }

    private function getArray($page, $sheet, $slug, $route, $locale){
        $configuration =$this->getDoctrine()->getRepository(Config::class, 'config')->findBy(['active' => true]);
        $array = $page->getActiveMenu($configuration, $sheet, $slug,$route, $locale);
        $array['locale'] = $locale;
        $entityManager = $this->getDoctrine()->getManager();
        $livredor = $entityManager->getRepository(Temoignage::class)->findBy(['active' => true]);
//        $blocks = $entityManager->getRepository(Block::class)->getBlocks();
//        $array['blocks'] = $blocks;

        $urls = $page->getSitemapByLocale($locale);
        $array['urls'] = $urls['html'];

        $array[ContactInterface::LIVREDOR] = $livredor;

        return $array;
    }

}
