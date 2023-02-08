<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait LocaleTrait
{
    /**
     * @Assert\Locale()
     * @ORM\Column(type="string" , nullable=true, options={"default":"fr"})
     */
    protected $locale = "fr";


    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale(string $locale = 'fr'): void
    {
        $this->locale = $locale;
    }

}
