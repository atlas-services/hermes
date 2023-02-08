<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait ReferenceNameTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, options={"default":"ref"})
     * @Assert\Length(max=100)
     */
    protected $referenceName ="ref";


    public function getReferenceName(): ?string
    {
        return $this->referenceName;
    }

    public function setReferenceName(?string $referenceName): void
    {
        $this->referenceName = $referenceName;
    }
}
