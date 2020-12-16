<?php

namespace App\Ecommerce;


use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderClient
{
    private $em;
    private $cartClient;
    public function __construct(EntityManagerInterface $em, CartClient $cartClient)
    {
        $this->em = $em;
        $this->cartClient = $cartClient;
    }


    public function handleCartProducts()
    {
        return $this->cartClient->getProducts();
        /*
         * @todo : handle cart to create Order and Order Lines
         */
        foreach($this->cartClient->getProducts() as $cartProduct){

        }

    }

    public function getTotal()
    {
        return $this->cartClient->getTotal();
        /*
         * @todo : handle cart to retrun Total amount of the Order
         */

    }

    public function emptyCart()
    {
        $this->cartClient->emptyCart();
    }


    public function addCustomer(?User $user)
    {
        if( $user instanceof User){
            $this->getCart()->setUser($user);
        }
    }


}