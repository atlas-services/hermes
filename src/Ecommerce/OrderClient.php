<?php

namespace App\Ecommerce;


use App\Entity\Delivery;
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

    public function redirect($user)
    {
        $order = $this->getCurrentOrderByUser($user);
        $status = $order->getStatus();
        switch ($status) {
            case Order::STATUS_CART:
                $route_name = 'cart_index';
                break;
            case Order::STATUS_ORDER:
                $route_name = 'order';
                break;
        }

        return $route_name;
    }

    public function getCartClient()
    {
        return $this->cartClient;
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
//        if(is_null($order)){
//            return $this->cartClient->getCart();
//        }
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
        $orderLines = $this->entityManager->getRepository(OrderLine::class)->findByOrder($order);
        return $orderLines;
    }

    public function handleCartProducts($user)
    {
        if(!is_null($user)) {
            $order = $this->getCurrentOrderByUser($user);
            // gestion order status CART
            // création order et orderLines "CART"
            $cartProducts = $this->cartClient->getProducts();
            // mise à jour order et orderLines "CART"
            if($order instanceof Order){
//                if ($this->hasOrderByUserAndStatus($user, Order::STATUS_CART) ) {
                if (in_array($order->getStatus(), Order::STATUS_CURRENT) ) {
                    $this->handleOrder($user, $order, $cartProducts,false);
                }
            }
            if(is_null($order)){
                $order = new Order();
                $this->handleOrder($user, $order, $cartProducts,true);
            }
        }
    }

    public function handleOrder($user, $order, $cartProducts, $add, $status = Order::STATUS_CART)
    {
        if ($add) {
            $order->setStatus(Order::STATUS_CART);
            $order->setName('order-' . $user->getFirstname() . '-' . rand(1, 9999));
            $order->setUser($user);
        }
        if (!$add) {
            $order->setStatus($status);
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

    public function handleDeliveryOrder($order, $delivery)
    {
        if(Delivery::DELIVERY_HOME ==  $delivery->getDeliveryMethod()){
            $delivery->setPrice(12345);
        }
        if(Delivery::DELIVERY_EXPRESS ==  $delivery->getDeliveryMethod()){
            $delivery->setPrice(54321);
        }
        $order->setDelivery($delivery);
        $order->setStatus(Order::STATUS_ORDER_PREPARE_DELIVERY);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

    }

    public function getTotal($user)
    {
        $total = 0;
        $delivery_price = 0;

        $order = $this->getCurrentOrderByUser($user);

        if($order instanceof Order){
            if($order->getDelivery() instanceof Delivery){
                $delivery_price = $order->getDelivery()->getPrice();
            }
        }

        $cart_total = $this->cartClient->getTotal();

        $total = $cart_total + $delivery_price;

        return $total;

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