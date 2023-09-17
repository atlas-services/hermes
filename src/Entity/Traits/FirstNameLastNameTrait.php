<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait FirstNameLastNameTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Length(max=100)
     */
    protected $firstname;


    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Length(max=100)
     */
    protected $lastname;


    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    public function setFirstName(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    public function setLastName(?string $lastname): void
    {
        $this->lastname = $lastname;
    }
}
