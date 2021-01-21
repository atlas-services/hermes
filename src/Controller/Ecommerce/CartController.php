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
use App\Entity\Product;
use App\Menu\Page;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartController extends AbstractController
{

    /**
     * @Route({
     * "fr": "/mon/panier",
     * "en": "/my/cart"
     * },
     * name="cart", methods={"GET|POST"})
     */
    public function mycart(Request $request, Page $page, CartClient $cartClient, OrderClient $orderClient, TranslatorInterface $translator): Response
    {
        if(0 == $cartClient->getTotal()){
            $cartClient->emptyCart();
            return $this->redirect('/');
        }

        // Récuperation de la commande en cours
        $order = $orderClient->getCurrentOrderByUser($this->getUser());

        $products = $cartClient->getProducts();
        $total = $cartClient->getTotal();

        $array = $page->getActiveMenu('accueil','accueil');
        $array['order'] = $order;
        $array['products'] = $products;
        $array['total'] = $total;
        $array['delivery']['free'] = ($array['ecommerce_delivery_free_amount'] != 0 && $total > $array['ecommerce_delivery_free_amount']);
        return $this->render('front/base/ecommerce/cart/index.html.twig', $array);

    }


    /**
     * @Route({
     * "fr": "/panier",
     * "en": "/cart"
     * },
     *     name="ajax_cart", methods={"GET|POST"})
     */
    public function cart(Request $request, Page $page, CartClient $cartClient ): Response
    {
        if ($request->isXMLHttpRequest()) {

            // add product to cart
            $id = $request->request->get('id');
            $quantity = $request->request->get('quantity');
            $type = $request->request->get('type');

            switch ($type) {
                case 'update':
                    $cartClient->update($id, $quantity);
                    break;
                case 'add':
                    $cartClient->add($id, $quantity);
                    break;
            }

            // get Total
            $total = $cartClient->getTotal();

            if(0 == $total){
                $cartClient->emptyCart();
                $navbar_cart_html = '';
                $cart_html= '';
            }else{
                $config = $page->getActiveConfig();
                $nav_link_color = $config['nav_link_color'];
                $products = $cartClient->getProducts();
                $navbar_cart_html= $this->renderView('front/base/navbar/cart.html.twig', ['nav_link_color' => $nav_link_color]);
                $cart_html = $this->renderView('front/base/ecommerce/cart/table.html.twig', ['products' =>$products, 'total' =>$total]);
            }
            $data['total'] = $total;
            $data['navbar_cart_html'] = $navbar_cart_html;
            $data['cart_html'] = $cart_html;
            return new JsonResponse(array('data' => $data));
        }

    }

    /**
     * @Route({
     * "fr": "/mon/panier/delete/{id}",
     * "en": "/my/cart/delete/{id}"
     * },
     * name="cart_delete", methods={"GET|POST"})
     */
    public function cart_remove($id, CartClient $cartClient): Response
    {
            $cartClient->update($id, 0);
            return $this->redirectToRoute("cart");

    }

}
