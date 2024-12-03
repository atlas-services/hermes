<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Hermes;

use App\Entity\AbstractContent;
use App\Entity\Hermes\User;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\PublishedTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
#[Vich\Uploadable]
#[ORM\Table(name: 'block_post')]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['block', 'name'], errorPath: 'name', message: 'post.exists')]
class BlockPost extends AbstractContent implements \JsonSerializable
{
    use PositionTrait;
    use PublishedTrait;
    /**
     * @var User
     */
    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Hermes\User::class, inversedBy: 'posts')]
    protected $user;

    /**
     * @var Block
     */
    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Hermes\Block::class, inversedBy: 'blockPosts')]
    protected $block;

    public function __toString(): string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getBlock()
    {
        return $this->block;
    }

    public function setBlock(?Block $block): void
    {
        $this->block = $block;
    }

    public function jsonSerialize()
    {
        $public = 'public';
        $src = '';
        if(!is_null($this->getImageFile())){
            $pos = strpos($this->getImageFile()->getPathname(), $public ) + strlen($public);
            $src = substr($this->getImageFile()->getPathname(), $pos );
        }

        return
            [
                'id'   => $this->getId(),
                'name' => $this->getName(),
                'content' => strip_tags($this->getContent()),
                'html_content' => $this->getContent(),
                'src' => $src,
            ];
    }

}
