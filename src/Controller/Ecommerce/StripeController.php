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
use App\Mailer\Mailer;
use App\Menu\Page;
use App\Ecommerce\StripeClient;
use GuzzleHttp\Psr7\UploadedFile;
use Knp\Snappy\Pdf;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupInterface;
use Twig\Environment;

class StripeController extends AbstractController
{

    /**
     * @Route({
     * "fr": "/mon/paiement/stripe",
     * "en": "/my/paiement/stripe"
     * },
     *     name="stripe_checkout", methods={"GET|POST"})
     */
    public function checkout(Request $request, Page $page, StripeClient $stripeClient, OrderClient $orderClient, TranslatorInterface $translator, Mailer $mailer,Environment $twig, EntrypointLookupInterface $entrypointLookup, Pdf $pdf): Response
    {
        if(!$this->isGranted('ROLE_CUSTOMER')){
            $notification = $translator->trans('paiement.message_compte');
            $this->addFlash('warning', $notification);
        }

        // Récuperation de la commande en cours
        // Mise à jour order
        $order = $orderClient->getCurrentOrderByUser($this->getUser());
        $orderClient->handleCartProducts($order, Order::STATUS_ORDER_PREPARE_PAIEMENT);

        // Récuperation de la commande en cours

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
                if(0 != $order->getDelivery()->getPrice()){
                    $delivery_name = $translator->trans('order.delivery_price') ;
                    $stripeClient->createInvoiceItem(
                        $order->getDelivery()->getPrice() ,
                        1 ,
                        $user,
                        $delivery_name . '('. $order->getDelivery()->getName() . ')',
                    );
                }else{
                    $delivery_name = $translator->trans('order.delivery_free') ;
                    $stripeClient->createInvoiceItem(
                        0 ,
                        1 ,
                        $user,
                        $delivery_name ,
                    );
                }
            }
            $stripeClient->createInvoice($user, true);
            $orderClient->emptyCart();
            $notification = $translator->trans('paiement.done');
            $this->addFlash('success', $notification);

            $orderClient->handleOrderPaiement($order);

            // send Email with order pdf
            $config = $page->getActiveConfig();
            $context = [
                'order' => $order,
                'products' => $orderLines,
                'total' => $order->getPrice(),
            ];
            $context = array_merge($context, $config);
            $template = 'front/contact/_includes/email_order.html.twig';
            $entrypointLookup->reset();
            $html = $twig->render('front/base/ecommerce/order/delivery_pdf.html.twig', $context);
            $pdf = $pdf->getOutputFromHtml($html);
            $orderClient->save($pdf,$order, $this->getParameter('kernel.project_dir'));

            $return = $mailer->sendOrder($this->getUser()->getUsername(), $this->getUser()->getEmail(), 'Commande', $template, $context,$pdf);
            $this->addFlash($return['type'], $return['message']);
            return $this->redirect('/');
        }

    }



}