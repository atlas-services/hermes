<?php

namespace App\Ecommerce;


use App\Entity\Delivery;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\OrderLineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderClient
{
    private $entityManager;
    private $cartClient;
    private $filesystem;

    public function __construct(EntityManagerInterface $entityManager, CartClient $cartClient, Filesystem $filesystem)
    {
        $this->entityManager = $entityManager;
        $this->cartClient = $cartClient;
        $this->filesystem = $filesystem;
    }

    public function getCartClient()
    {
        return $this->cartClient;
    }

    public function getCurrentOrderByUser($user)
    {
        $order =  $this->entityManager->getRepository(Order::class)->findOneBy([ 'user'=> $user, 'status'=> Order::STATUS_CURRENT]);
        if(is_null($order)){
            $order = new Order();
            $order->setStatus(Order::STATUS_CART);
            $order->setUser($user);
            $order->setName('order-' . $user->getFirstname() . '-' . rand(1, 9999));
        }
        return $order;
    }

    public function handleCartProducts(Order $order, $status = Order::STATUS_CART)
    {

        if(!is_null($order)) {
            // gestion order status CART
            $cartProducts = $this->cartClient->getProducts();
            // mise à jour order et orderLines "CART"
            if (in_array($order->getStatus(), Order::STATUS_CURRENT) ) {
                $this->handleOrderProducts($order, $cartProducts, $status);
            }
        }
    }

    public function handleOrderProducts($order, $cartProducts, $status = Order::STATUS_CART)
    {
        $order->setStatus($status);
        foreach($order->getOrderLines() as $orderLine){
            $order->removeOrderLine($orderLine);
            $this->entityManager->remove($orderLine);
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

    public function handleOrderDelivery($order, $delivery, $ecommerce_delivery_free_amount=0)
    {

        if($delivery) {
            $this->updateDeliveryPrice($delivery, $ecommerce_delivery_free_amount);

            if(is_null($delivery->getName())){
                $delivery->setName($delivery->getDeliveryMethod() . '-' . $order->getId());
            }
            $order->setDelivery($delivery);
            $order->setStatus(Order::STATUS_ORDER_PREPARE_DELIVERY);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        }

    }

    public function updateDeliveryPrice($delivery, $ecommerce_delivery_free_amount=0)
    {
        $delivery_free = false;
        if(0 != $ecommerce_delivery_free_amount){
            $cart_total = $this->cartClient->getTotal();
            if($cart_total >= $ecommerce_delivery_free_amount){
                $delivery_free = true;
                $delivery->setPrice(0);
            }
        }

        if(!$delivery_free){
            if($delivery){
                if(Delivery::DELIVERY_HOME ==  $delivery->getDeliveryMethod()){
                    $delivery->setPrice(12345);
                }
                if(Delivery::DELIVERY_HOME_EXPRESS ==  $delivery->getDeliveryMethod()){
                    $delivery->setPrice(54321);
                }
            }
        }
    }

    public function handleOrderPaiement($order)
    {
        $order->setStatus(Order::STATUS_PAYED);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

    }

    public function getTotal(Order $order)
    {
        $total = 0;
        $delivery_price = 0;
        if($order->getDelivery() instanceof Delivery){
            $delivery_price = $order->getDelivery()->getPrice();
        }

        $cart_total = $this->cartClient->getTotal();

        $total = $cart_total + $delivery_price;

        if($order instanceof Order) {
            $order->setPrice($total);
        }

        return $total;

    }

    public function getTotalProducts()
    {
        $total = 0;

        return $this->cartClient->getTotal();

    }

    public function emptyCart()
    {
        $this->cartClient->emptyCart();
    }

    public function save($file,$order, $project_dir)
    {
        $user = $order->getUser();
        $order_dir = $project_dir.'/public/data/orders/'.$user->getId();
        $this->filesystem->mkdir($order_dir);
        $this->filesystem->dumpFile(sprintf($order_dir.'/order-%s.pdf', $order->getId()), $file);
    }

}