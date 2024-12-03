<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;

trait ActiveNewsletterTrait
{
    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    protected $active_newsletter= true;


    public function isActiveNewsletter(): ?bool
    {
        return $this->active_newsletter;
    }

    public function setActiveNewsletter(?bool $active_newsletter): void
    {
        $this->active_newsletter = $active_newsletter;
    }

}
