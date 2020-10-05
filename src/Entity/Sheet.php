<?php

namespace App\Entity;

use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\CodeTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\ImageTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\SummaryTrait;
use App\Entity\Traits\UpdatedTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SheetRepository")
 * @UniqueEntity(
 *     "name",
 *      message="sheet.exists"
 *  )
 * @ORM\Table(name="sheet")
 * Defines the properties of the Sheet entity to represent the blog menus.
 *
 * See https://symfony.com/doc/current/book/doctrine.html#creating-an-entity-class
 *
 * Tip: if you have an existing database, you can generate these entity class automatically.
 * See https://symfony.com/doc/current/cookbook/doctrine/reverse_engineering.html
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 *  * @Vich\Uploadable
 */
class Sheet
{
    use IdTrait;
    use ActiveTrait;
    use CodeTrait;
    use NameTrait;
    use SlugTrait;
    use SummaryTrait;
    use PositionTrait;
    use ImageTrait;
    use UpdatedTrait;

    /**
     * @var Menu[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Menu", mappedBy="sheet",  cascade={"persist", "remove"})
     * @ORM\JoinTable(name="menu_sheet")
     */
    private $menus;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
    }

    public function __toString(): ?string
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
