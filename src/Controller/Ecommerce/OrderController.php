<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Ecommerce;

use App\Ecommerce\OrderClient;
use App\Entity\Product;
use App\Menu\Page;
use App\Ecommerce\StripeClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderController extends AbstractController
{

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
        // Creation order et orderlines
        $orderClient->handleCartProducts($this->getUser());
        // Récuperation commande
        $products = $orderClient->getProducts($this->getUser());

        $total = $orderClient->getTotal();

        $array = $page->getActiveMenu('accueil','accueil');
        $array['products'] = $products;
        $array['total'] = $total;
        return $this->render('front/base/ecommerce/order/index.html.twig', $array);

    }

}
