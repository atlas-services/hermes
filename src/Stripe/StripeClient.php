<?php

namespace App\Stripe;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class StripeClient
{
    private $em;
    public function __construct($secretKey, EntityManagerInterface $em)
    {
        $this->em = $em;
        \Stripe\Stripe::setApiKey($secretKey);
    }
    public function createCustomer(User $user, $paymentToken)
    {
        $customer = \Stripe\Customer::create([
            'email' => $user->getEmail() ,
            'name' => $user->getFirstname(). ' ' .  $user->getFirstname(),
            'source' => $paymentToken,
        ]);
        $user->setStripeCustomerId($customer->id);
        $this->em->persist($user);
        $this->em->flush($user);
        return $customer;
    }
    public function updateCustomerCard(User $user, $paymentToken)
    {
        $customer = \Stripe\Customer::retrieve($user->getStripeCustomerId());
        $customer->source = $paymentToken;
        $customer->save();
    }
    public function createInvoiceItem($unit_amount, $quantity, User $user, $description)
    {
        return \Stripe\InvoiceItem::create(array(
            "unit_amount" => $unit_amount,
            "quantity" => $quantity,
            "currency" => $user->getCurrency(),
            "customer" => $user->getStripeCustomerId(),
            "description" => $description
        ));
    }
    public function createInvoice(User $user, $payImmediately = true)
    {
        $invoice = \Stripe\Invoice::create(array(
            "customer" => $user->getStripeCustomerId()
        ));
        if ($payImmediately) {
            // guarantee it charges *right* now
            $invoice->pay();
        }
        return $invoice;
    }
}