<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait PriceTrait
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(max=4)
     */
    protected $price = 1 ;

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

}
