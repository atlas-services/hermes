<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;

trait DefaultInvoiceTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean" , nullable=true)
     */
    protected $defaultInvoice= true;


    public function isDefaultInvoice(): ?bool
    {
        return $this->defaultInvoice;
    }

    public function setDefaultInvoice(?bool $defaultInvoice): void
    {
        $this->defaultInvoice = $defaultInvoice;
    }

}
