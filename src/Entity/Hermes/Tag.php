<?php

namespace App\Entity\Hermes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\Collection;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;

/**
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
#[ORM\Table(name: 'tag')]
#[ORM\Entity]
class Tag
{
    use IdTrait;
    use NameTrait;

    #[ORM\JoinTable(name: 'post_tag')]
    #[ORM\ManyToMany(targetEntity: \App\Entity\Hermes\Post::class, mappedBy: 'tags', cascade: ['persist'])]
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
