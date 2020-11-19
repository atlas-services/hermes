<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait PublishedTrait
{

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $startPublishedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $endPublishedAt;


    /**
     * @return \DateTime
     */
    public function getStartPublishedAt(): ?\DateTime
    {
        return $this->startPublishedAt;
    }

    /**
     * @param \DateTime $startPublishedAt
     */
    public function setStartPublishedAt(?\DateTime $startPublishedAt): void
    {
        $this->startPublishedAt = $startPublishedAt;
    }

    /**
     * @return \DateTime
     */
    public function getEndPublishedAt(): ?\DateTime
    {
        return $this->endPublishedAt;
    }

    /**
     * @param \DateTime $endPublishedAt
     */
    public function setEndPublishedAt(?\DateTime $endPublishedAt): void
    {
        $this->endPublishedAt = $endPublishedAt;
    }


}
