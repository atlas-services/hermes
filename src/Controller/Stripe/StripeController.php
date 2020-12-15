<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Stripe;


use App\Cart\OrderClient;
use App\Entity\Product;
use App\Menu\Page;
use App\Stripe\Cart;
use App\Stripe\StripeClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class StripeController extends AbstractController
{

    /**
     * @Route({
     * "fr": "/ma/commande",
     * "en": "/my/order"
     * },
     *     name="stripe_checkout", methods={"GET|POST"})
     */
    public function checkout(Request $request, Page $page, StripeClient $stripeClient, OrderClient $orderClient, TranslatorInterface $translator): Response
    {
        if(!$this->isGranted('ROLE_USER')){
            $notification = $translator->trans('cart.message_compte');
            $this->addFlash('warning', $notification);
        }

        $products = $orderClient->handleCartProducts();
        $total = $orderClient->getTotal();

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
            $orderClient->emptyCart();
            $notification = $translator->trans('cart.paiement_done');
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

}
