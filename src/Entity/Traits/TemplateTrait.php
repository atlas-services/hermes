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
    public function getTemplate2(): ?Template
    {
        return $this->template2;
    }

    public function setTemplate2(?Template $template2): void
    {
        $this->template2 = $template2;
    }
}
