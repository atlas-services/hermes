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
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryRepository")
 * @ORM\Table(name="delivery")
 */
class Delivery
{
    use IdTrait;
    use ActiveTrait;
    use NameTrait;
    use PriceTrait;
    use UpdatedTrait;

    const DELIVERY_CC =  'CLICK_AND_COLLECT' ;
    const DELIVERY_RELAY =  'RELAIS' ;
    const DELIVERY_HOME =  'HOME' ;
    const DELIVERY_EXPRESS =  'EXPRESS' ;
    const DELIVERY_ERROR =  'ERROR' ;

    const DELIVERY_ALL =  [
        self::DELIVERY_CC => self::DELIVERY_CC,
        self::DELIVERY_RELAY => self::DELIVERY_RELAY,
        self::DELIVERY_HOME => self::DELIVERY_HOME,
        self::DELIVERY_EXPRESS => self::DELIVERY_EXPRESS,
        self::DELIVERY_ERROR => self::DELIVERY_ERROR,
    ] ;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    protected $delivery_method;

    /**
     * @var Order[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Order",  mappedBy="delivery",  cascade={"persist", "remove"})
     * @ORM\JoinTable(name="delivery_order")
     */
    protected $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->delivery_method = self::DELIVERY_CC;
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
    public function getDeliveryMethod(): string
    {
        return $this->delivery_method;
    }

    /**
     * @param string $delivery_method
     */
    public function setDeliveryMethod(string $delivery_method): void
    {
        if(in_array($delivery_method,self::DELIVERY_ALL)){
            $this->delivery_method = $delivery_method;
        }
    }

    public function getOrders(): ?Collection
    {
        return $this->orders ;
    }

}
