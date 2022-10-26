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
use App\Entity\Traits\UrlTrait;
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
 *     fields={"section", "name"},
 *     errorPath="name",
 *     message="post.exists"
 * )
 * @ORM\Table(name="post")
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
class Post extends AbstractContent
{
    use PositionTrait;
    use UrlTrait;
    use PublishedTrait;
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Hermes\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * @var Tag[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Hermes\Tag", inversedBy="posts",  cascade={"persist"})
     * @ORM\JoinTable(name="post_tag")
     * @Assert\Count(max="40", maxMessage="post.too_many_tags")
     */
    private $tags;

    /**
     * @var Section
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Hermes\Section", inversedBy="posts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $section;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

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

    public function addTag(?Tag ...$tags): void
    {
        foreach ($tags as $tag) {
            if (!$this->tags->contains($tag)) {
                $this->tags->add($tag);
            }
        }
    }

    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    public function getTags(): ?Collection
    {
        return $this->tags;
    }

    public function getSection()
    {
        return $this->section;
    }

    public function setSection(?Section $section): void
    {
        $this->section = $section;
    }

}
