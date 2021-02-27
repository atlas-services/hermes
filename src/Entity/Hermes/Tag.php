<?php

namespace App\Entity\Hermes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\Collection;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tag")
 *
 * Defines the properties of the Tag entity to represent the post tags.
 *
 * See https://symfony.com/doc/current/book/doctrine.html#creating-an-entity-class
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class Tag
{
    use IdTrait;
    use NameTrait;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Hermes\Post", mappedBy="tags", cascade={"persist"})
     * @ORM\JoinTable(name="post_tag")
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * Add posts
     *
     * @param Post $posts
     * @return Tag
     */
    public function addPost(Post $posts)
    {
        if (!$this->posts->contains($posts)) {
            $this->posts[] = $posts;
            $posts->addTag($this);
        }

        return $this;
    }

    /**
     * Remove posts
     *
     * @param Post $posts
     */
    public function removePost(Post $posts)
    {
        $this->posts->removeElement($posts);
        $posts->removeTag($this);
    }

    /**
     * Get posts
     *
     * @return Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

}
