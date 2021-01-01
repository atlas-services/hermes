<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;

trait DefaultDeliveryTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean" , nullable=true)
     */
    protected $defaultDelivery= true;


    public function isDefaultDelivery(): ?bool
    {
        return $this->defaultDelivery;
    }

    public function setDefaultDelivery(?bool $defaultDelivery): void
    {
        $this->defaultDelivery = $defaultDelivery;
    }

}
