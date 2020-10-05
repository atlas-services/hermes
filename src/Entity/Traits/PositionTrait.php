<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait PositionTrait
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(max=2)
     */
    protected $position = 1 ;


    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

}
