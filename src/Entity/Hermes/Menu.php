<?php

namespace App\Entity\Hermes;


use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\CodeTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\ImageTrait;
use App\Entity\Traits\LocaleTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\ReferenceNameTrait;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\SummaryTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\Traits\UserTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
#[Vich\Uploadable]
#[ORM\Table(name: 'menu')]
#[ORM\Entity(repositoryClass: \App\Repository\MenuRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['locale', 'referenceName'], message: 'menu.exists')] // Defines the properties of the Tag entity to represent the menu.
class Menu
{
    use IdTrait;
    use ActiveTrait;
    use LocaleTrait;
    use ReferenceNameTrait;
    use CodeTrait;
    use NameTrait;
    use PositionTrait;
    use SlugTrait;
    use UserTrait;
    use ImageTrait;
    use UpdatedTrait;

    /**
     * @var User
     */
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Hermes\User::class, inversedBy: 'menus')]
    protected $user;

    /**
     * @var Sheet
     */
    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Hermes\Sheet::class, inversedBy: 'menus')]
    private $sheet;

    /**
     * @var Section[]|ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: \App\Entity\Hermes\Section::class, mappedBy: 'menu', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['position' => 'ASC'])]
    protected $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
    }


    public function __toString(): string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }

    public function addSection(?Section ...$sections): void
    {
        foreach ($sections as $section) {
            if (!$this->sections->contains($section)) {
                if($section->isActive()){
                    $this->sections->add($section);
                    $section->setMenu($this);
                }
            }
        }
    }

    public function removeSection(Section $section): void
    {
        $this->sections->removeElement($section);
        $section->setMenu(null);
    }

    public function getSections(): ?Collection
    {
        foreach ($this->sections as $section){
            if(!$section->isActive()){
                $this->removeSection($section);
            }
        }
        return $this->sections;
    }



    /**
     * @return Sheet
     */
    public function getSheet(): ?Sheet
    {
        return $this->sheet;
    }

    /**
     * @param Sheet $sheet
     */
    public function setSheet(Sheet $sheet): void
    {
        $this->sheet = $sheet;
    }

}
