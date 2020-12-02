<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Stripe;


use App\Cart\CartClient;
use App\Entity\Product;
use App\Menu\Page;
use App\Stripe\Cart;
use App\Stripe\StripeClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{

    /**
     * @Route("/checkout", name="stripe_checkout", methods={"GET|POST"})
     */
    public function checkout(Request $request, Page $page, StripeClient $stripeClient, CartClient $cartClient): Response
    {
        if(!$this->isGranted('ROLE_USER')){
            $notification = "Afin de pouvoir commander un produit, vous devez vous connecter avec un compte acheteur";
            $this->addFlash('danger', $notification);
            return $this->redirect('/');
        }

        $products = $cartClient->getProducts();
        $total = $cartClient->getTotal();

        $public_key = $this->getParameter('stripe_public_key');
        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            /** @var User $user */
            $user = $this->getUser();
            if (!$user->getStripeCustomerId()) {
                $stripeClient->createCustomer($user, $token);
            } else {
                $stripeClient->updateCustomerCard($user, $token);
            }
            // Get Products from cart

            foreach ($products as $product) {
                $stripeClient->createInvoiceItem(
                    $product->getProduct()->getPrice() ,
                    $product->getQuantity() ,
                    $user,
                    $product->getName(),
                );
            }
            $stripeClient->createInvoice($user, true);
            $cartClient->emptyCart();
            $notification= 'Order Complete! Yay!';
            $this->addFlash('success', $notification);
            return $this->redirect('/');
        }

        $array = $page->getActiveMenu('accueil','accueil');
        $array['APP_STRIPE_PK'] = $public_key;
        $array['products'] = $products;
        $array['total'] = $total;
        return $this->render('front/base/stripe/checkout.html.twig', $array);

        return $this->render('order/checkout.html.twig', array(
            'products' => $products,
            'total' => $total,
            'cart' => $this->get('shopping_cart')
        ));

    }


    /**
     * @Route("/cart", name="cart", methods={"GET|POST"})
     */
    public function cart(Request $request, Page $page, CartClient $cartClient): Response
    {
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('id');
            $quantity = $request->request->get('quantity');
            // add product to cart
            $cartClient->createCartProduct($id,$quantity);
            $cartClient->addCustomer($this->getUser());

            return new JsonResponse(array('data' => 'ok'));
        }

    }

}
