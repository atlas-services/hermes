<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait ContentTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(groups={"content"}, message="error_message.post.content")
     */
    protected $content;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): ?self
    {
        $this->content = $content;
        return $this;
    }

}
