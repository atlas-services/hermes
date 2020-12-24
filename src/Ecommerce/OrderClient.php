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

    public function countOrderByUserAndStatus($user, $status = Order::STATUS_CART)
    {
        $order =  $this->entityManager->getRepository(Order::class)->findBy([ 'user'=> $user, 'status'=> $status]);

        return count($order);
    }

    public function hasOrderByUserAndStatus($user, $status = Order::STATUS_CART)
    {
        $order =  $this->entityManager->getRepository(Order::class)->findBy([ 'user'=> $user, 'status'=> $status]);
        if(count($order) > 0){
            return true;
        }
        return false;
    }

    public function getCurrentOrderByUser($user)
    {
        $order =  $this->entityManager->getRepository(Order::class)->findOneBy([ 'user'=> $user, 'status'=> Order::STATUS_CURRENT]);
        return $order;
    }

    public function updateOrderStatus($user, $status_from = Order::STATUS_CART, $status_to = Order::STATUS_ORDER)
    {

        $order =  $this->entityManager->getRepository(Order::class)->findOneBy(['user'=> $user, 'status'=> $status_from]);
        if(!is_null($order)){
            $order->setStatus($status_to);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
            // empty cart
//            if( 1 == $this->countOrderByUserAndStatus($user, Order::STATUS_CART) && 0 == $this->countOrderByUserAndStatus($user, Order::STATUS_ORDER)){
            if( $order->getStatus() == Order::STATUS_PAYED){
                $this->cartClient->emptyCart();
            }
        }
    }

    public function getOrdersByUserAndStatus($user, $status = Order::STATUS_CART)
    {
       return  $this->entityManager->getRepository(Order::class)->findBy(['user'=> $user, 'status'=> $status]);
    }

    public function getProducts($user, $status = Order::STATUS_CART)
    {
        $order =  $this->entityManager->getRepository(Order::class)->findOneBy(['user'=> $user, 'status'=> $status]);
//        dump($order);
//        foreach($order->getOrderLines() as $orderLine){
//            dump($orderLine);
//        }

        $orderLines = $this->entityManager->getRepository(OrderLine::class)->findByOrder($order);
        return $orderLines;
    }

    public function handleCartProducts($user)
    {
        if(!is_null($user)) {
            $order = $this->getCurrentOrderByUser($user);
            // gestion order status CART
//            if ($this->hasOrderByUserAndStatus($user, Order::STATUS_CART) ) {
//                $order = $this->getCartOrderByUser($user);
//                $this->updateOrderStatus($user, Order::STATUS_CART, Order::STATUS_ORDER);
//            }

            // création order et orderLines
            $cartProducts = $this->cartClient->getProducts();
            if(!is_null($order)){
                $this->handleOrder($user, $order, $cartProducts,false);
            }
            if(is_null($order)){
                $order = new Order();
                $this->handleOrder($user, $order, $cartProducts,true);
            }

        }
    }

    public function handleOrder($user, $order, $cartProducts, $add)
    {
        if ($add) {
            $order->setStatus(Order::STATUS_CART);
            $order->setName('order-' . $user->getFirstname() . '-' . rand(1, 9999));
            $order->setUser($user);
        }
        if (!$add) {
            $order->setStatus(Order::STATUS_ORDER);
            foreach($order->getOrderLines() as $orderLine){
                $order->removeOrderLine($orderLine);
                $this->entityManager->remove($orderLine);
            }
        }
        foreach($cartProducts as $cartProduct){
            $orderLine = new OrderLine();
            $orderLine->setProduct($cartProduct['product']);
            $orderLine->setQuantity($cartProduct['quantity']);
            $orderLine->setOrder($order);
            $this->entityManager->persist($orderLine);
            $order->addOrderLine($orderLine);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

    }

    public function getTotal($bDelivery = false)
    {
        $total = 0;

        $delivery = $this->getDeliveryPrice($bDelivery);

        $cart_total = $this->cartClient->getTotal();

        $total = $cart_total + $delivery;

        return $total;

    }
    
    public function getDeliveryPrice($bDelivery = false)
    {
        $delivery = 0;
        if(true == $bDelivery){
            $delivery = 10000;
        }
        return $delivery;

    }

    public function updateOrdersStatusByUser($user, $status_from = Order::STATUS_ORDER, $status_to = Order::STATUS_WAITING)
    {
        $orders = $this->getOrdersByUserAndStatus($user,$status_from);
        foreach ($orders as $order){
            $order->setStatus($status_to);
            $this->entityManager->persist($order);
        }
        $this->entityManager->flush();
    }

    public function emptyOrderByUserAndStatus($user, $status = Order::STATUS_CART)
    {
        $orders = $this->getOrdersByUserAndStatus($user,$status);
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