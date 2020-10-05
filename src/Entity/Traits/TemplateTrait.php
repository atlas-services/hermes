<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\Template;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait TemplateTrait
{

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): void
    {
        $this->template = $template;
    }
}
