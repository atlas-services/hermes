<?php

namespace App\Entity;

use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\PriceTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\Traits\UserTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service_locator;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="orders")
 */
class Order
{
    use IdTrait;
    use ActiveTrait;
    use NameTrait;
    use UserTrait;
    use PriceTrait;
    use UpdatedTrait;

    const STATUS_CART =  'CART' ;
    const STATUS_ORDER =  'ORDER' ;
    const STATUS_ORDER_PREPARE_DELIVERY =  'ORDER_PREPARE_DELIVERY' ;
    const STATUS_ORDER_PREPARE_PAIEMENT =  'ORDER_PREPARE_PAIEMENT' ;
    const STATUS_WAITING =  'WAITING' ;
    const STATUS_PAYED =  'PAYED' ;
    const STATUS_CANCEL =  'CANCEL' ;
    const STATUS_ERROR =  'ERROR' ;

    const STATUS_CURRENT =  [
        self::STATUS_CART => self::STATUS_CART,
        self::STATUS_ORDER => self::STATUS_ORDER,
        self::STATUS_ORDER_PREPARE_DELIVERY => self::STATUS_ORDER_PREPARE_DELIVERY,
        self::STATUS_ORDER_PREPARE_PAIEMENT => self::STATUS_ORDER_PREPARE_PAIEMENT,
    ] ;

    const STATUS_CHANGE =  [
        self::STATUS_WAITING => self::STATUS_WAITING,
        self::STATUS_CANCEL => self::STATUS_CANCEL,
    ] ;

    const STATUS_ALL =  [
        self::STATUS_CART => self::STATUS_CART,
        self::STATUS_ORDER => self::STATUS_ORDER,
        self::STATUS_ORDER_PREPARE_DELIVERY => self::STATUS_ORDER_PREPARE_DELIVERY,
        self::STATUS_ORDER_PREPARE_PAIEMENT => self::STATUS_ORDER_PREPARE_PAIEMENT,
        self::STATUS_WAITING => self::STATUS_WAITING,
        self::STATUS_PAYED => self::STATUS_PAYED,
        self::STATUS_CANCEL => self::STATUS_CANCEL,
        self::STATUS_ERROR => self::STATUS_ERROR,
    ] ;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    protected $status;

    /**
     * @var OrderLine[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\OrderLine",  mappedBy="order",  cascade={"persist", "remove"})
     * @ORM\JoinTable(name="product_order")
     */
    protected $order_lines;

    /**
     * @var Delivery
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Delivery", inversedBy="orders",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $delivery;

    public function __construct()
    {
        $this->order_lines = new ArrayCollection();
        $this->status = self::STATUS_CART;
        $this->updatedAt = new \DateTime("NOW");
    }

    public function __toString(): ?string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        if(in_array($status,self::STATUS_ALL)){
            $this->status = $status;
        }
    }

    public function addOrderLine(?OrderLine ...$orderLines): void
    {
        foreach ($orderLines as $orderLine) {
            if (!$this->order_lines->contains($orderLine)) {
                if (0 != $orderLine->getQuantity()) {
                    $this->order_lines->add($orderLine);
                    $orderLine->setOrder($this);
                }
            }
        }
    }

    public function removeOrderLine(OrderLine $orderLine): void
    {
        $this->order_lines->removeElement($orderLine);
        $orderLine->setOrder(null);
    }

    public function getOrderLines(): ?Collection
    {
        return $this->order_lines ;
    }

    /**
     * @return Delivery
     */
    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    /**
     * @param Delivery $delivery
     */
    public function setDelivery(Delivery $delivery): void
    {
        $this->delivery = $delivery;
    }

}
