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
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"block", "name"},
 *     errorPath="name",
 *     message="post.exists"
 * )
 * @ORM\Table(name="block_post")
 *
 * Defines the properties of the Post entity to represent the blog posts.
 *
 * See https://symfony.com/doc/current/book/doctrine.html#creating-an-entity-class
 *
 * Tip: if you have an existing database, you can generate these entity class automatically.
 * See https://symfony.com/doc/current/cookbook/doctrine/reverse_engineering.html
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 * @Vich\Uploadable
 */
class BlockPost extends AbstractContent implements \JsonSerializable
{
    use PositionTrait;
    use PublishedTrait;
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Hermes\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * @var Block
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Hermes\Block", inversedBy="blockPosts")
     * @ORM\JoinColumn(nullable=true)
     */
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
