<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Ecommerce;


use App\Ecommerce\OrderClient;
use App\Entity\Delivery;
use App\Entity\Order;
use App\Entity\Product;
use App\Menu\Page;
use App\Ecommerce\StripeClient;
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
     * "fr": "/mon/paiement/stripe",
     * "en": "/my/paiement/stripe"
     * },
     *     name="stripe_checkout", methods={"GET|POST"})
     */
    public function checkout(Request $request, Page $page, StripeClient $stripeClient, OrderClient $orderClient, TranslatorInterface $translator): Response
    {
        if(!$this->isGranted('ROLE_CUSTOMER')){
            $notification = $translator->trans('paiement.message_compte');
            $this->addFlash('warning', $notification);
        }

//  /    // Récuperation de la commande en cours
        // Mise à jour order et raz du panier
        $orderClient->handleCartProducts($this->getUser(), Order::STATUS_ORDER_PREPARE_DELIVERY);

        // Récuperation de la commande en cours
        $order = $orderClient->getCurrentOrderByUser($this->getUser());
        if(!$order instanceof Order){
            return $this->redirectToRoute('cart');
        }else{
            $orderLines = $order->getOrderLines();
        }

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

            foreach ($orderLines as $product) {
                $stripeClient->createInvoiceItem(
                    $product->getPrice() ,
                    $product->getQuantity() ,
                    $user,
                    $product->getProduct()->getName(),
                );
            }
            if($order->getDelivery() instanceof Delivery){
                $delivery_name = $translator->trans('order.delivery_price') ;
                $stripeClient->createInvoiceItem(
                    $order->getDelivery()->getPrice() ,
                    1 ,
                    $user,
                    $delivery_name . '('. $order->getDelivery()->getName() . ')',
                );
            }
            $stripeClient->createInvoice($user, true);
            $orderClient->emptyCart();
            $notification = $translator->trans('paiement.done');
            $this->addFlash('success', $notification);

            $orderClient->handlePaiementOrder($order);
            return $this->redirect('/');
        }

        $total = $orderClient->getTotal($this->getUser());
        $array = $page->getActiveMenu('accueil','accueil');
        $array['APP_STRIPE_PK'] = $public_key;
        $array['order'] = $order;
        $array['products'] = $orderLines ?? $products;
        $array['total'] = $total;
        return $this->render('front/base/ecommerce/paiement/stripe/index.html.twig', $array);

    }

}
