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
 * @ORM\Entity(repositoryClass="App\Repository\SectionRepository")
 * @ORM\Table(name="section")
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
class Section
{
    use ActiveTrait;
    use IdTrait;
    use TemplateTrait;
    use RemoteTrait;
    use PositionTrait;
    use NameTrait;
    use UserTrait;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sections")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * @var Posts[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Post",  mappedBy="section",  cascade={"persist", "remove"})
     * @ORM\JoinTable(name="post_section")
     */
    protected $posts;

    /**
     * @var Template
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Template", inversedBy="sections")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $template;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(max=3)
     */
    protected $template_width;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(max=1)
     */
    protected $template_nb_col;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $template_image_filter;

    /**
     * @var Template
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Template", inversedBy="sections")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $template2;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(max=3)
     */
    protected $template2_width;

    /**
     * @var Remote
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Remote", inversedBy="sections")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $remote;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Menu", inversedBy="sections")
     * @ORM\JoinColumn(
     *      name="menu",
     *      referencedColumnName="id",
     *      onDelete="CASCADE",
     *      nullable=true
     * )
     */
    protected $menu;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }

    public function addPost(?Post ...$posts): void
    {
        foreach ($posts as $post) {
            if (!$this->posts->contains($post)) {
                if($post->isActive()){
                    $this->posts->add($post);
                    $post->setSection($this);
                }
            }
        }
    }

    public function removePost(Post $post): void
    {
        $this->posts->removeElement($post);
        $post->setSection(null);
    }

    public function getActivePosts(): ?Collection
    {
        foreach ($this->posts as $post){
            if(!$post->isActive()){
                $this->removePost($post);
            }
        }
        return $this->posts;
    }


    public function getPosts(): ?Collection
    {
        $now = (new \DateTime("NOW"))->format('Y-m-d');
        foreach ($this->posts as $post){
            if($post->getStartPublishedAt()){
                if($post->getStartPublishedAt()->format('Y-m-d') > $now){
                    $this->removePost($post);
                }
            }
            if($post->getEndPublishedAt()){
                if($post->getEndPublishedAt()->format('Y-m-d') < $now){
                    $this->removePost($post);
                }
            }
            if(!$post->isActive()){
                $this->removePost($post);
            }
        }
        return $this->posts;
    }

    public function getPrevisualisationPosts(): ?Collection
    {
        return $this->posts;
    }

    /**
     * @return mixed
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param mixed $menu
     */
    public function setMenu(?Menu $menu): ?Section
    {
        $this->menu = $menu;
        return $this;
    }

    /**
     * @return int
     */
    public function getTemplateWidth(): int
    {
        if( '' == $this->template_width){
            return '70';
        }
        return $this->template_width;
    }

    /**
     * @param int $template_width
     */
    public function setTemplateWidth(int $template_width): void
    {
        $this->template_width = $template_width;
    }

    /**
     * @return int
     */
    public function getTemplateNbCol(): int
    {
        if( '' == $this->template_nb_col){
            return 4 ;
        }
        return $this->template_nb_col;
    }

    /**
     * @param int $template_nb_col
     */
    public function setTemplateNbCol(int $template_nb_col): void
    {
        $this->template_nb_col = $template_nb_col;
    }

    /**
     * @return string
     */
    public function getTemplateImageFilter(): string
    {
        if( '' == $this->template_image_filter){
            return 'bd_154' ;
        }
        return $this->template_image_filter;
    }

    /**
     * @param string $template_image_filter
     */
    public function setTemplateImageFilter(string $template_image_filter): void
    {
        $this->template_image_filter = $template_image_filter;
    }

    /**
     * @return int
     */
    public function getTemplate2Width(): int
    {
        if( '' == $this->template2_width){
            return '50';
        }
        return $this->template2_width;
    }

    /**
     * @param int $template2_width
     */
    public function setTemplate2Width(int $template2_width): void
    {
        $this->template2_width = $template2_width;
    }

}
