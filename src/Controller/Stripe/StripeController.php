<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Stripe;


use App\Entity\Product;
use App\Menu\Page;
use App\Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{

//    /**
//     * @Route("/stripe/checkout", name="stripe_checkout", methods={"GET|POST"})
//     */
//    public function checkout(Request $request, Page $page): Response
//    {
//        $stripe = $this->getParameter('stripe');
//        $api_key = $stripe['secret_key'];
//        $public_key = $stripe['public_key'];
//        if ($request->isMethod('POST')) {
//            $token = $request->request->get('stripeToken');
//            \Stripe\Stripe::setApiKey($api_key);
//            \Stripe\Charge::create(array(
////                "amount" => $this->get('shopping_cart')->getTotal() * 100,
//                "amount" =>189,
//                "currency" => "eur",
//                "source" => $token,
//                "description" => "First test charge!"
//            ));
////            $this->get('shopping_cart')->emptyCart();
//            $notification= 'Order Complete! Yay!';
//            $this->addFlash('success', $notification);
//            $array['notification'] = $notification;
//            return $this->redirect('/');
//        }
//
//        $array = $page->getActiveMenu('accueil','accueil');
//        $array['APP_STRIPE_PK']=$public_key;
//        return $this->render('front/base/stripe/checkout.html.twig', $array);
//
//        return $this->render('order/checkout.html.twig', array(
//            'products' => $products,
//            'cart' => $this->get('shopping_cart')
//        ));
//
//    }

    /**
     * @Route("/stripe/checkout", name="stripe_checkout", methods={"GET|POST"})
     */
    public function checkout(Request $request, Page $page, StripeClient $stripeClient): Response
    {
        $api_key = $this->getParameter('stripe_secret_key');
        $public_key = $this->getParameter('stripe_public_key');
        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
//            $stripeClient = $this->get('stripe_client');
            /** @var User $user */
            $user = $this->getUser();
            if (!$user->getStripeCustomerId()) {
                $stripeClient->createCustomer($user, $token);
            } else {
                $stripeClient->updateCustomerCard($user, $token);
            }
            // Get Products from cart
//            $products = $this->get('shopping_cart')->getProducts();
            $product1 = new Product();
            $product1->setName('P01');
            $product1->setPrice(25);
            $product2 = new Product();
            $product2->setName('P02');
            $product2->setPrice(75);
            $products= [$product1,$product2];
            foreach ($products as $product) {
                $stripeClient->createInvoiceItem(
                    $product->getPrice() * 100,
                    $user,
                    $product->getName()
                );
            }
            $stripeClient->createInvoice($user, true);
//            $this->get('shopping_cart')->emptyCart();
            $notification= 'Order Complete! Yay!';
            $this->addFlash('success', $notification);
            return $this->redirect('/');
        }

        $array = $page->getActiveMenu('accueil','accueil');
        $array['APP_STRIPE_PK'] = $public_key;
        return $this->render('front/base/stripe/checkout.html.twig', $array);

        return $this->render('order/checkout.html.twig', array(
            'products' => $products,
            'cart' => $this->get('shopping_cart')
        ));

    }

}
