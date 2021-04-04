<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait UrlTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Url(
     *  protocols = {"http", "https"}
     * )
     */
    protected $url;


    public function getIdUrl(): ?string
    {
        $replace = 'embed/';
        $idUrl = str_replace($replace, '', strstr($this->url, $replace ));
        return $idUrl;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }
}
