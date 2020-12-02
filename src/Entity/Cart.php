<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\Traits\UserTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cart")
 */
class Cart
{
    use IdTrait;
    use NameTrait;
    use UserTrait;
    use UpdatedTrait;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="carts")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * @var CartProducts[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\CartProduct",  mappedBy="cart",  cascade={"persist", "remove"})
     * @ORM\JoinTable(name="product_cart")
     */
    protected $cart_products;

    public function __construct()
    {
        $this->cart_products = new ArrayCollection();
    }


    public function __toString(): ?string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }

    public function addCartProduct(?CartProduct ...$cartProducts): void
    {
        foreach ($cartProducts as $cartProduct) {
            if (!$this->cart_products->contains($cartProduct)) {
                $this->cart_products->add($cartProduct);
                $cartProduct->setCart($this);
            }
        }
    }

    public function removeCartProduct(CartProduct $cartProduct): void
    {
        $this->cart_products->removeElement($cartProduct);
        $cartProduct->setCart(null);
    }

    public function getCartProducts(): ?Collection
    {
//        foreach ($this->cart_products as $cartProduct){
//            $this->removeCartProduct($cartProduct);
//        }
        return $this->cart_products ;
    }




}
