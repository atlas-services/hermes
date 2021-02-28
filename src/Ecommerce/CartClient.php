<?php

namespace App\Ecommerce;

use App\Entity\Hermes\Product;
use App\Entity\Hermes\User;
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

    public function getCart()
    {
        return $this->session->get('cart', [] );
    }

    public function add(int $id, int $quantity)
    {
        $cart = $this->getCart();
        if(!empty($cart[$id])){
            $cart[$id] = $cart[$id] + $quantity;
        }else{
            $cart[$id] = $quantity;
        }
        $this->session->set('cart', $cart );
    }

    public function update(int $id, int $quantity)
    {
        $cart = $this->getCart();
        if(!empty($cart[$id])){
            if(0 == $quantity){
                unset($cart[$id]);
            }else{
                $cart[$id] = $quantity;
            }
        }
        $this->session->set('cart', $cart );
    }

    public function getProducts()
    {
        $productCart = [];
         foreach ($this->getCart() as $id => $quantity ){
             if(0 != $quantity){
                 $productCart[] = [
                     'product' => $this->em->getRepository("App:Product")->find($id),
                     'quantity' => $quantity,
                     'price' => $quantity * $this->em->getRepository("App:Product")->find($id)->getPrice() ,
                 ];
             }

        }
        return $productCart;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getProducts() as $cartProduct ){
            $total += $cartProduct['quantity'] * $cartProduct['product']->getPrice();
        }

        return $total;
    }

    public function emptyCart()
    {
        $this->session->remove('cart');
    }

}