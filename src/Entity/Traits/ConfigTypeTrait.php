<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait ConfigTypeTrait
{
    /**
     * @var string
     */
    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Length(max: 50)]
    protected $type;


    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }
}
