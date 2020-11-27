<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Front;

use App\Entity\Contact;
use App\Entity\Interfaces\ContactInterface;
use App\Entity\Temoignage;
use App\Form\ContactType;
use App\Form\Admin\TemoignageType;
use App\Mailer\Mailer;
use App\Menu\Page;
use App\Repository\PostRepository;
use App\Repository\TemoignageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class FrontController extends AbstractController
{

    /**
     * @Route("/stripe/checkout", name="stripe_checkout", methods={"GET"})
     */
    public function checkout(Request $request, Page $page): Response
    {

        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            \Stripe\Stripe::setApiKey("sk_test_51HrKP8AVdmNJM9zMBXtvPsor94Td1S0WvHd2ovALphLhMeJ28SoTh8zBsgpzpGwvt9A3cTIAbeyNTQRk3lsHTTce003iMtQqi2");
            \Stripe\Charge::create(array(
//                "amount" => $this->get('shopping_cart')->getTotal() * 100,
                "amount" =>189,
                "currency" => "eur",
                "source" => $token,
                "description" => "First test charge!"
            ));
            $this->get('shopping_cart')->emptyCart();
            $this->addFlash('success', 'Order Complete! Yay!');
            return $this->redirectToRoute('homepage');
        }

        $array = $page->getActiveMenu('accueil','accueil');
        return $this->render('front/base/stripe/checkout.html.twig', $array);

        return $this->render('order/checkout.html.twig', array(
            'products' => $products,
            'cart' => $this->get('shopping_cart')
        ));


    }
    /**

     * @Route(
     *     "/search",
     *     name="search_content",
     *     methods={"GET|POST"}
     *     )
     */
    public function search(Request $request, PostRepository $postRepository, TemoignageRepository $temoignageRepository, RouterInterface $router, Page $page)
    {
        $referer = $request->headers->get('referer');
        $refererPathInfo = Request::create($referer)->getPathInfo();
        $refererPathInfo = str_replace($request->getScriptName(), '', $refererPathInfo);
        $routeInfos = $router->match($refererPathInfo);
        if(ContactInterface::CONTACT == $routeInfos['_route'] || ContactInterface::LIVREDOR_ROUTE == $routeInfos['_route']){
            $route = $routeInfos['_route'];
            $array = $page->getActiveMenu(ContactInterface::LIVREDOR_TEXTE, ContactInterface::LIVREDOR_TEXTE, $route);
        }else{
            $sheet = $routeInfos['sheet'];
            $slug = $routeInfos['slug'];
            $array = $page->getActiveMenu($sheet, $slug);
        }

        $q = $request->query->get('q');
        $posts = [
            'posts' => array_merge($postRepository->findAllWithSearch($q),$temoignageRepository->findAllWithSearch($q) ),
        ];

        $array = array_merge($array, $posts);

        return $this->render('front/search_result.html.twig', $array);

    }


    /**
     * @Route(
     *     "/contact",
     *     name="contact",
     *     methods={"GET|POST"}
     *     )
     * @Route(
     *     "/livre-d-or",
     *     name="livre-d-or",
     *     methods={"GET|POST"}
     *     )
     */
    public function form(Request $request, CacheInterface $backCache, Mailer $mailer, Page $page, $sheet = 'accueil', $slug = 'accueil')
    {
        $route = $request->attributes->get('_route');
        if (ContactInterface::LIVREDOR_ROUTE == $route) {
            $array = $page->getActiveMenu(ContactInterface::LIVREDOR_TEXTE, ContactInterface::LIVREDOR_TEXTE, $route);
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
            $array = $page->getActiveMenu(ContactInterface::CONTACT, ContactInterface::CONTACT,$route);
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
     * @Route(
     *     "/{sheet}/{slug}",
     *     name="sheet",
     *     methods={"GET|POST"}
     *     )
     * @Route(
     *     "/{slug}",
     *     name="slug",
     *     methods={"GET|POST"}
     *     )
     */
    public function page(Request $request, CacheInterface $frontCache, Mailer $mailer, Page $page, $sheet = 'accueil', $slug = 'accueil')
    {
        $route = $request->attributes->get('_route');
        if ('contact' == $sheet) {
            return $this->redirectToRoute('contact');
        }
        if ('livre-d-or' == $sheet) {
            return $this->redirectToRoute('livre-d-or');
        }

        if ('ACCUEIL' != strtoupper($sheet)) {
            if (strtoupper($slug) == strtoupper($sheet)) {
                return $this->redirectToRoute('slug', ['slug' => $slug]);
            }
        }

/*
 * @todo cache remote sauf si sheet, menu section sont modifiés ?
 */
//        $cache = $page->getCacheMenu($sheet, $slug);
//        if(!empty($cache)){
//                    // The callable will only be executed on a cache miss.
//                $response = $frontCache->get($cache['front_cache'], function (ItemInterface $item) use($page, $sheet, $slug, $route) {
//
//                    $array = $page->getActiveMenu($sheet, $slug, $route);
//                    $cached_response = $this->render('front/index.html.twig', $array);
//
//
//                    return $cached_response ;
//                });
//
//                return $response;
//        }


        $array = $page->getActiveMenu($sheet, $slug,$route);

        return $this->render('front/index.html.twig', $array);
    }


}
