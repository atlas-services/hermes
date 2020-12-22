<?php

namespace App\Ecommerce;


use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\OrderLineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderClient
{
    private $entityManager;
    private $cartClient;
    public function __construct(EntityManagerInterface $entityManager, CartClient $cartClient)
    {
        $this->entityManager = $entityManager;
        $this->cartClient = $cartClient;
    }


    public function hasOrder($user,$status = Order::STATUS_CART)
    {
        $order =  $this->entityManager->getRepository(Order::class)->findBy(['status'=> $status, 'user'=> $user]);
        if(count($order) > 0){
            return true;
        }
        return false;
    }

    public function getOrders($user, $status = Order::STATUS_CART)
    {
       return  $this->entityManager->getRepository(Order::class)->findBy(['status'=> $status, 'user'=> $user]);
    }

    public function getProducts($user, $status = Order::STATUS_CART)
    {
        $order =  $this->entityManager->getRepository(Order::class)->findBy(['status'=> $status, 'user'=> $user]);
        $orderLines = $this->entityManager->getRepository(OrderLine::class)->findByOrder($order);
        return $orderLines;
    }


    public function handleCartProducts($user)
    {
        $bLine=false;
        $this->emptyOrder($user);
        if (!$this->hasOrder($user, Order::STATUS_CART)){
            $order = new Order();
            if(!is_null($user)){
                $order->setName('order-' . $user->getFirstname(). '-'.rand(1 , 9999));
                $order->setUser($user);
                foreach($this->cartClient->getProducts() as $cartProduct){
                    $orderLine = new OrderLine();
                    $orderLine->setProduct($cartProduct['product']);
                    $orderLine->setQuantity($cartProduct['quantity']);
                    $orderLine->setOrder($order);
                    $this->entityManager->persist($orderLine);
                    $bLine= true;
                }
                if($bLine){
                    $this->entityManager->persist($order);
                    $this->entityManager->flush();
                }
            }
        }
    }

    public function getTotal($bLivraison = false)
    {
        $livraison = 0;
        $total = 0;

        if(true == $bLivraison){
            $livraison = 10000;
        }
        $cart_total = $this->cartClient->getTotal();

        $total = $cart_total + $livraison;

        return $total;

    }

    public function emptyOrder($user)
    {
        $orders = $this->getOrders($user);
        foreach ($orders as $order){
            $this->entityManager->remove($order);
        }
        $this->entityManager->flush();
    }

    public function emptyCart()
    {
        $this->cartClient->emptyCart();
    }


    public function addCustomer(?User $user)
    {
        if( $user instanceof User){
            $this->getOrder()->setUser($user);
        }
    }


}