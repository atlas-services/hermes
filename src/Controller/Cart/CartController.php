<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Cart;


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
    public function mycart(Request $request, Page $page, CartClient $cartClient, TranslatorInterface $translator): Response
    {
        if(0 == $cartClient->getTotal()){
            $cartClient->emptyCart();
            return $this->redirect('/');
        }
        if(!$this->isGranted('ROLE_USER')){
            $notification = $translator->trans('cart.message_compte');
            $this->addFlash('warning', $notification);
        }

        $products = $cartClient->getProducts();
        $total = $cartClient->getTotal();


        $array = $page->getActiveMenu('accueil','accueil');
        $array['products'] = $products;
        $array['total'] = $total;
        return $this->render('front/base/cart/index.html.twig', $array);

    }


    /**
     * @Route({
     * "fr": "/panier",
     * "en": "/cart"
     * },
     *     name="ajax_cart", methods={"GET|POST"})
     */
    public function cart(Request $request, Page $page, CartClient $cartClient): Response
    {
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('id');
            $quantity = $request->request->get('quantity');
            // add product to cart
            $cartClient->createCartProduct($id,$quantity);
            $total = $cartClient->getTotal();
            if(!is_null($this->getUser())){
                $cartClient->addCustomer($this->getUser());
            }
            if(0 == $total){
                $cartClient->emptyCart();
                $navbar_cart_html = '';
                $cart_html= '';
            }else{
                $products = $cartClient->getProducts();
                $navbar_cart_html= $this->renderView('front/base/navbar/cart.html.twig');
                $cart_html = $this->renderView('front/base/cart/table.twig', ['products' =>$products, 'total' =>$total]);
            }
            $data['total'] = $total;
            $data['navbar_cart_html'] = $navbar_cart_html;
            $data['cart_html'] = $cart_html;
            return new JsonResponse(array('data' => $data));
        }

    }

}
