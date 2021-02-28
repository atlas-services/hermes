<?php

namespace App\Entity\Hermes;

use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\CodeTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\PriceTrait;
use App\Entity\Traits\SummaryTrait;
use App\Entity\Traits\UpdatedTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="product")
 */
class Product
{
    use IdTrait;
    use ActiveTrait;
    use CodeTrait;
    use NameTrait;
    use PriceTrait;
    use SummaryTrait;
    use UpdatedTrait;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Hermes\Post",inversedBy="product")
     */
    protected $post;

    /**
     * @var OrderLine
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Hermes\OrderLine", inversedBy="product")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $order_product;

    /**
     * @return OrderLine
     */
    public function getOrderProduct(): OrderLine
    {
        return $this->order_product;
    }

    /**
     * @param OrderLine $order_product
     */
    public function setOrderProduct(OrderLine $order_product): void
    {
        $this->order_product = $order_product;
    }

    public function __toString(): ?string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }

    /**
     * @return Post
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(?Post $post): void
    {
        $this->post = $post;
    }



}
