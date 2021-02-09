<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\TemplateTrait;
use App\Entity\Traits\RemoteTrait;
use App\Entity\Traits\UserTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlockRepository")
 * @ORM\Table(name="block")
 *
 * Defines the properties of the Block entity to represent the blog posts.
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
class Block
{
    use ActiveTrait;
    use IdTrait;
    use NameTrait;
    use UserTrait;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="blocks")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * @var BlockPosts[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\BlockPost",  mappedBy="block",  cascade={"persist", "remove"})
     * @ORM\JoinTable(name="blockpost_block")
     */
    protected $blockPosts;


    public function __construct()
    {
        $this->blockPosts = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }

    public function addBlockPost(?BlockPost ...$blockPosts): void
    {
        foreach ($blockPosts as $blockPost) {
            if (!$this->blockPosts->contains($blockPost)) {
                if($blockPost->isActive()){
                    $this->blockPosts->add($blockPost);
                    $blockPost->setBlock($this);
                }
            }
            $this->blockPosts->add($blockPost);
            $blockPost->setBlock($this);

        }
    }

    public function removeBlockPost(BlockPost $blockPost): void
    {
        $this->blockPosts->removeElement($blockPost);
        $blockPost->setBlock(null);
    }

    public function getActiveBlockPosts(): ?Collection
    {
        foreach ($this->blockPosts as $blockPost){
            if(!$blockPost->isActive()){
                $this->removeBlockPost($blockPost);
            }
        }
        return $this->blockPosts;
    }


    public function getBlockPosts(): ?Collection
    {
        $now = (new \DateTime("NOW"))->format('Y-m-d');
        foreach ($this->blockPosts as $blockPost){
            if($blockPost->getStartPublishedAt()){
                if($blockPost->getStartPublishedAt()->format('Y-m-d') > $now){
                    $this->removeBlockPost($blockPost);
                }
            }
            if($blockPost->getEndPublishedAt()){
                if($blockPost->getEndPublishedAt()->format('Y-m-d') < $now){
                    $this->removeBlockPost($blockPost);
                }
            }
            if(!$blockPost->isActive()){
                $this->removeBlockPost($blockPost);
            }
        }
        return $this->blockPosts;
    }


}
