<?php

namespace App\Cart;


use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartClient
{
    private $em;
    private $session;
    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    public function createCart()
    {
        if(!$this->session->has('cart')){
            $cart = new Cart();
            $this->session->set('cart', $cart );
        }else{
            $cart = $this->session->get('cart' );
        }
        return $cart;
    }

    public function handleCartProducts(Product $product, $quantity=1 )
    {
        $has_cart = $this->session->has('cart');
        if(true == $has_cart){
            $cart = $this->session->get('cart');
            foreach($cart->getCartProducts() as $cartProduct){
                if($cartProduct->getProduct()->getId() == $product->getId() ){
                    if(0 == $quantity){
                        $this->removeCartProduct($cart, $cartProduct);
                        break;
                    }
                    if(1 == $quantity){
                        $quantity = $cartProduct->getQuantity() + $quantity;
                    }
                    $cartProduct->setQuantity($quantity);
                    $this->addCartProduct($cart, $cartProduct);
                    return true;
                }
            }
        }
        return false;
    }

    public function addCustomer(?User $user)
    {
        if( $user instanceof User){
            $this->createCart()->setUser($user);
        }
    }

    public function createCartProduct($id,  $quantity = 1)
    {
        $product = $this->em->getRepository(Product::class)->findOneById($id);
        if(true != $this->handleCartProducts($product,$quantity)) {
            if(0!= $quantity){
                $cartProduct = new CartProduct();
                $cartProduct->setProduct($product);
                $cartProduct->setQuantity($quantity);

                $cart = $this->createCart();
                $this->addCartProduct($cart, $cartProduct);
            }
        }
    }

    public function addCartProduct(Cart $cart, CartProduct $cartProduct)
    {
        $cart->addCartProduct($cartProduct);
        $this->session->set('cart' , $cart);
    }

    public function removeCartProduct(Cart $cart, CartProduct $cartProduct)
    {
        $cart->removeCartProduct($cartProduct);
        $this->session->set('cart' , $cart);
    }

    public function getProducts()
    {
        $cartProducts = [];
        $cart = $this->session->get('cart' );
        if(isset($cart)){
            $cartProducts = $cart->getCartProducts();
        }
        return $cartProducts;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getProducts() as $cartProduct ){
            $total += $cartProduct->getPrice();
        }

        return $total;
    }

    public function emptyCart()
    {
        $this->session->remove('cart');
    }

}