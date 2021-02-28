<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Ecommerce;

use App\Ecommerce\CartClient;
use App\Ecommerce\OrderClient;
use App\Entity\Config\Config;
use App\Entity\Hermes\Address;
use App\Entity\Hermes\Delivery;
use App\Entity\Hermes\Order;
use App\Entity\Hermes\Product;
use App\Form\Admin\Ecommerce\AddressType;
use App\Form\Admin\Ecommerce\DeliveryType;
use App\Menu\Page;
use App\Ecommerce\StripeClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderController extends AbstractController
{

    /**
     * @Route({
     * "fr": "/mon/compte",
     * "en": "/my/account"
     * },
     * name="order_account", methods={"GET|POST"})
     */
    public function myorder_account(Request $request,AuthenticationUtils $authenticationUtils, Page $page, CartClient $cartClient, OrderClient $orderClient, TranslatorInterface $translator): Response
    {
        $referer = $request->headers->get('referer'); // get the referer
        if($this->isGranted('ROLE_CUSTOMER')){
            // Mise à jour order et raz du panier
            if(true == strpos($referer, 'compte')) {
                $order = $orderClient->getCurrentOrderByUser($this->getUser());
                $orderClient->handleCartProducts($order);
                return $this->redirectToRoute('order_delivery');
            }
        }
        $configuration = $this->getDoctrine()->getManager('config')->getRepository(Config::class, 'config')->findBy(['active' => true]);
        $array = $page->getActiveMenu($configuration,'accueil','accueil');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $account = ['last_username' => $lastUsername, 'error' => $error];

        // Récuperation du panier en cours
        $products = $cartClient->getProducts();
        $total = $cartClient->getTotal();
        $array['products'] = $orderLines ?? $products;
        $array['delivery']['free'] = ($array['ecommerce_delivery_free_amount'] != 0 && $orderClient->getTotalProducts() > $array['ecommerce_delivery_free_amount']);
        $array['total'] = $total;
        $array= array_merge($array, $account);
        return $this->render('front/base/ecommerce/order/login.html.twig', $array);

    }

    /**
     * @Route({
     * "fr": "/ma/livraison",
     * "en": "/my/delivery"
     * },
     * name="order_delivery", methods={"GET|POST"})
     */
    public function myorder_delivery(Request $request, Page $page, OrderClient $orderClient, TranslatorInterface $translator): Response
    {
        if(!$this->isGranted('ROLE_CUSTOMER')){
            return $this->redirectToRoute('cart');
        }

        $order = $orderClient->getCurrentOrderByUser($this->getUser());
        if(0 == $orderClient->getTotal($order)){
            $orderClient->emptyCart();
            return $this->redirect('/');
        }
        $configuration = $this->getDoctrine()->getManager('config')->getRepository(Config::class, 'config')->findBy(['active' => true]);
        $array = $page->getActiveMenu($configuration,'accueil','accueil');

        $orderClient->handleCartProducts($order);
        $orderClient->handleOrderDelivery($order, $order->getDelivery(), $array['ecommerce_delivery_free_amount']);

        $form = $this->createForm(DeliveryType::class);
        $form->handleRequest($request);

        // Récuperation de la commande en cours

        if ($form->isSubmitted() && $form->isValid()) {
            $delivery = $form->getData();
            $orderClient->handleOrderDelivery($order, $delivery, $array['ecommerce_delivery_free_amount']);
            return $this->redirectToRoute('order_paiement');
        }

        if(!$order instanceof Order){
            return $this->redirectToRoute('cart');
        }else{
            $orderLines = $order->getOrderLines();
        }

        $total = $orderClient->getTotal($order);
        $total_products = $orderClient->getTotalProducts();
        $array['order'] = $order;
        $array['form'] = $form->createView();
        $array['products'] = $orderLines ;
        $array['delivery']['free'] = ($array['ecommerce_delivery_free_amount'] != 0 && $orderClient->getTotalProducts() > $array['ecommerce_delivery_free_amount']);
        $array['total'] = $total;
        return $this->render('front/base/ecommerce/order/delivery.html.twig', $array);

    }
    /**
     * @Route({
     * "fr": "/mon/paiement",
     * "en": "/my/paiement"
     * },
     * name="order_paiement", methods={"GET|POST"})
     */
    public function myorder_paiement(Request $request, Page $page, OrderClient $orderClient, TranslatorInterface $translator): Response
    {

        if(!$this->isGranted('ROLE_CUSTOMER')){
            return $this->redirectToRoute('cart');
        }

        $order = $orderClient->getCurrentOrderByUser($this->getUser());
        if(0 == $orderClient->getTotal($order)){
            $orderClient->emptyCart();
            return $this->redirect('/');
        }
        $configuration = $this->getDoctrine()->getManager('config')->getRepository(Config::class, 'config')->findBy(['active' => true]);
        $array = $page->getActiveMenu($configuration,'accueil','accueil');


        // Mise à jour order et raz du panier
        $orderClient->handleCartProducts($order, Order::STATUS_ORDER_PREPARE_DELIVERY);
        $orderClient->handleOrderDelivery($order, $order->getDelivery(), $array['ecommerce_delivery_free_amount']);
        // Récuperation de la commande en cours
        if(!$order instanceof Order){
            return $this->redirectToRoute('cart');
        }else{
            $orderLines = $order->getOrderLines();
        }

        $stripe_public_key = $this->getParameter('stripe_public_key');
        $total = $orderClient->getTotal($order);

        $array['order'] = $order;
        $array['products'] = $orderLines ?? $products;
        $array['delivery']['free'] = ($array['ecommerce_delivery_free_amount'] != 0 && $orderClient->getTotalProducts() > $array['ecommerce_delivery_free_amount']);
        $array['total'] = $total;
        $array['APP_STRIPE_PK'] = $stripe_public_key;
        return $this->render('front/base/ecommerce/order/paiement.html.twig', $array);

    }

    /**
     * @Route("/customer/delivery/delivery-method-select", name="customer_delivery_delivery_method_select")
     */
    public function getAddressSelect(Request $request, OrderClient $orderClient, TranslatorInterface $translator)
    {
        if(!$this->isGranted('ROLE_CUSTOMER')){
            return $this->redirectToRoute('cart');
        }

        $order = $orderClient->getCurrentOrderByUser($this->getUser());
        if(0 == $orderClient->getTotal($order)){
            $orderClient->emptyCart();
            return $this->redirect('/');
        }

        //Delivery
        $delivery = new Delivery();
        $delivery->setDeliveryMethod($request->query->get('deliveryMethod'));
        $form = $this->createForm(DeliveryType::class, $delivery);
        // no field? Return an empty response
        if (!$form->has('address')) {
            return new Response(null, 204);
        }

        $array['form'] = $form->createView();

        return $this->render('front/base/ecommerce/order/delivery_address.html.twig', $array);

    }

    /**
     * @Route("/customer/delivery/address-new", name="customer_delivery_address_new")
     */
    public function setNewAddress(Request $request, OrderClient $orderClient, TranslatorInterface $translator)
    {
        if(!$this->isGranted('ROLE_CUSTOMER')){
            return $this->redirectToRoute('cart');
        }

        $order = $orderClient->getCurrentOrderByUser($this->getUser());
        if(0 == $orderClient->getTotal($order)){
            $orderClient->emptyCart();
            return $this->redirect('/');
        }

        //Address
        $address = new Address();
        $address->setUser($this->getUser());
        $address->setFamilyName($this->getUser()->getLastname());
        $address->setGivenName($this->getUser()->getFirstname());
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('order_delivery');
        }

        $array['form'] = $form->createView();

        return $this->render('front/base/ecommerce/order/address_form.html.twig', $array);

    }

}
