<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cartProduct")
 */
class CartProduct
{
    use IdTrait;

    /**
     * @var Product
     * @ORM\OneToOne(targetEntity="App\Entity\Product",mappedBy="cart_product")
     */
    protected $product;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\Length(max=2)
     */
    protected $quantity;

    /**
     * @var Cart
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Cart", inversedBy="cart_products")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $cart;

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
     * @return Cart
     */
    public function getCart(): Cart
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     */
    public function setCart(?Cart $cart): void
    {
        $this->cart = $cart;
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
