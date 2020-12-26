<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Ecommerce;

use App\Ecommerce\OrderClient;
use App\Entity\Order;
use App\Entity\Product;
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
    public function myorder_account(Request $request,AuthenticationUtils $authenticationUtils, Page $page, OrderClient $orderClient, TranslatorInterface $translator): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $account = ['last_username' => $lastUsername, 'error' => $error];

         if(0 == $orderClient->getTotal()){
            $orderClient->emptyCart();
            return $this->redirect('/');
        }

        if($orderClient->countOrderByUserAndStatus($this->getUser(), Order::STATUS_ORDER) > 1){
            // Status ORDER to WAITING for old order with 'ORDER' status
            $orderClient->updateOrdersStatusByUser($this->getUser(), Order::STATUS_ORDER,  Order::STATUS_WAITING);
            $notification = $translator->trans('order.message_exists');
            $this->addFlash('warning', $notification);
        }

        // Mise à jour order et raz du panier
        $orderClient->handleCartProducts($this->getUser());

        // Récuperation de la commande en cours
        $order = $orderClient->getCurrentOrderByUser($this->getUser());
        if(is_object($order)){
            $orderLines = $order->getOrderLines();
        }else{
            $products = $orderClient->getCartClient()->getProducts();
        }

        $bDelivery = true;
        $delivery_price = $orderClient->getDeliveryPrice($bDelivery);
        $total = $orderClient->getTotal($bDelivery);

        $array = $page->getActiveMenu('accueil','accueil');
        $array['order'] = $order;
        $array['products'] = $orderLines ?? $products;
        $array['delivery_price'] = $delivery_price;
        $array['total'] = $total;
        $array= array_merge($array, $account);
        return $this->render('front/base/ecommerce/order/login.html.twig', $array);

    }
    /**
     * @Route({
     * "fr": "/ma/commande",
     * "en": "/my/order"
     * },
     * name="order", methods={"GET|POST"})
     */
    public function myorder(Request $request, Page $page, OrderClient $orderClient, TranslatorInterface $translator): Response
    {

        if(0 == $orderClient->getTotal()){
            $orderClient->emptyCart();
            return $this->redirect('/');
        }

        if(!$this->isGranted('ROLE_CUSTOMER')){
            $notification = $translator->trans('order.message_compte');
            $this->addFlash('warning', $notification);
        }

        if($orderClient->countOrderByUserAndStatus($this->getUser(), Order::STATUS_ORDER) > 1){
            // Status ORDER to WAITING for old order with 'ORDER' status
            $orderClient->updateOrdersStatusByUser($this->getUser(), Order::STATUS_ORDER,  Order::STATUS_WAITING);
            $notification = $translator->trans('order.message_exists');
            $this->addFlash('warning', $notification);
        }

        // Mise à jour order et raz du panier
        $orderClient->handleCartProducts($this->getUser());

        // Récuperation de la commande en cours
        $order = $orderClient->getCurrentOrderByUser($this->getUser());
        if(is_object($order)){
            $orderLines = $order->getOrderLines();
        }else{
            $products = $orderClient->getCartClient()->getProducts();
        }

        $bDelivery = true;
        $delivery_price = $orderClient->getDeliveryPrice($bDelivery);
        $total = $orderClient->getTotal($bDelivery);

        $array = $page->getActiveMenu('accueil','accueil');
        $array['order'] = $order;
        $array['products'] = $orderLines ?? $products;
        $array['delivery_price'] = $delivery_price;
        $array['total'] = $total;
        return $this->render('front/base/ecommerce/order/index.html.twig', $array);

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

        if(0 == $orderClient->getTotal()){
            $orderClient->emptyCart();
            return $this->redirect('/');
        }

        if(!$this->isGranted('ROLE_CUSTOMER')){
            $notification = $translator->trans('order.message_compte');
            $this->addFlash('warning', $notification);
        }

        if($orderClient->countOrderByUserAndStatus($this->getUser(), Order::STATUS_ORDER) > 1){
            // Status ORDER to WAITING for old order with 'ORDER' status
            $orderClient->updateOrdersStatusByUser($this->getUser(), Order::STATUS_ORDER,  Order::STATUS_WAITING);
            $notification = $translator->trans('order.message_exists');
            $this->addFlash('warning', $notification);
        }

        // Mise à jour order et raz du panier
        $orderClient->handleCartProducts($this->getUser());

        // Récuperation de la commande en cours
        $order = $orderClient->getCurrentOrderByUser($this->getUser());
        if(is_object($order)){
            $orderLines = $order->getOrderLines();
        }else{
            $products = $orderClient->getCartClient()->getProducts();
        }

        $bDelivery = true;
        $delivery_price = $orderClient->getDeliveryPrice($bDelivery);
        $total = $orderClient->getTotal($bDelivery);

        $array = $page->getActiveMenu('accueil','accueil');
        $array['order'] = $order;
        $array['products'] = $orderLines ?? $products;
        $array['delivery_price'] = $delivery_price;
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

        if(0 == $orderClient->getTotal()){
            $orderClient->emptyCart();
            return $this->redirect('/');
        }

        if(!$this->isGranted('ROLE_CUSTOMER')){
            $notification = $translator->trans('order.message_compte');
            $this->addFlash('warning', $notification);
        }

        if($orderClient->countOrderByUserAndStatus($this->getUser(), Order::STATUS_ORDER) > 1){
            // Status ORDER to WAITING for old order with 'ORDER' status
            $orderClient->updateOrdersStatusByUser($this->getUser(), Order::STATUS_ORDER,  Order::STATUS_WAITING);
            $notification = $translator->trans('order.message_exists');
            $this->addFlash('warning', $notification);
        }

        // Mise à jour order et raz du panier
        $orderClient->handleCartProducts($this->getUser());

        // Récuperation de la commande en cours
        $order = $orderClient->getCurrentOrderByUser($this->getUser());
        if(is_object($order)){
            $orderLines = $order->getOrderLines();
        }else{
            $products = $orderClient->getCartClient()->getProducts();
        }

        $bDelivery = true;
        $delivery_price = $orderClient->getDeliveryPrice($bDelivery);
        $total = $orderClient->getTotal($bDelivery);

        $array = $page->getActiveMenu('accueil','accueil');
        $array['order'] = $order;
        $array['products'] = $orderLines ?? $products;
        $array['delivery_price'] = $delivery_price;
        $array['total'] = $total;
        return $this->render('front/base/ecommerce/order/paiement.html.twig', $array);

    }

}
