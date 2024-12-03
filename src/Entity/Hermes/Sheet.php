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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
#[Vich\Uploadable]
#[ORM\Table(name: 'sheet')]
#[ORM\Entity(repositoryClass: \App\Repository\SheetRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['locale', 'referenceName'], message: 'sheet.exists')] // Defines the properties of the Sheet entity to represent the blog menus.
class Sheet
{
    const ONE_PAGE = 'one-page';
    const ONE_PAGE_LIBELLE = 'one page';

    use IdTrait;
    use ActiveTrait;
    use LocaleTrait;
    use ReferenceNameTrait;
    use CodeTrait;
    use NameTrait;
    use SlugTrait;
    use SummaryTrait;
    use PositionTrait;
    use ImageTrait;
    use UpdatedTrait;

    /**
     * @var Menu[]|ArrayCollection
     */
    #[ORM\JoinTable(name: 'menu_sheet')]
    #[ORM\OneToMany(targetEntity: \App\Entity\Hermes\Menu::class, mappedBy: 'sheet', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private $menus;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
    }

    public function __toString(): string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }

    public function addMenu(?Menu ...$menus): void
    {
        foreach ($menus as $menu) {
            if (!$this->menus->contains($menu)) {
                if($menu->isActive()){
                    $this->menus->add($menu);
                }
            }
        }
    }

    public function removeMenu(Menu $menu): void
    {
        $this->menus->removeElement($menu);
        $menu->setSheet(null);
    }

    public function getMenus(): ?Collection
    {
        foreach ($this->menus as $menu){
            if(!$menu->isActive()){
                $this->removeMenu($menu);
            }
        }
        return $this->menus;
    }

}
