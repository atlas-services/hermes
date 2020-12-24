<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderLineRepository")
 * @ORM\Table(name="orderLine")
 */
class OrderLine
{
    use IdTrait;

    /**
     * @var Product
     * @ORM\OneToOne(targetEntity="App\Entity\Product",mappedBy="order_product")
     */
    protected $product;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\Length(max=2)
     */
    protected $quantity;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="order_lines")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $order;

    public function __toString(): ?string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->quantity * $this->getProduct()->getPrice();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getProduct()->getName();
    }


}
