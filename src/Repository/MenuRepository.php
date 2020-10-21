<?php

namespace App\Repository;

use App\Entity\Menu;
use App\Entity\Sheet;
use App\Repository\Traits\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

/**
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    /**
     * @return int Returns max position value
     */

    public function getMaxPosition(Sheet $sheet = null)
    {
        $qb = $this->getQbMaxPosition();
        if (isset($sheet)) {
            $qb
                ->where('m.sheet = :sheet')
                ->setParameter(':sheet', $sheet->getId());
        }
        $list = $qb
            ->getQuery()
            ->getResult();

        if (isset($list[0])) {
            return ++$list[0]['position'];
        }
        if (isset($list['position'])) {
            return ++$list['position'];
        }
        return 1;
    }

    public function getQbMenus($active = true)
    {
        $qb = $this->createQueryBuilder('m')
            ->join('m.sheet', 'sheet')
            ->andWhere('sheet.active = true ')
            ->orderBy('sheet.position', 'ASC')
            ->addOrderBy('m.position', 'ASC')
        ;

        if($active){
            $qb->where('m.active = :active')
                ->setParameter('active', $active)
            ;
        }

        return $qb;
    }

    public function getMenus()
    {
        try {
            $list = $this->getQbMenus(true)
                ->getQuery()
                ->getResult()
            ;
//            dd($list);
            if([] == $list){
                return [];
            }
            foreach ($list as $menu){
                $menus[$menu->getSheet()->getName()][$menu->getName()] = $menu;
            }

            $list = $menus;

        }catch (\Exception $e){
            return [];
        }

        return $list;
    }

    public function getMyMenuBySheetAndMenuSlugs($sheet_slug, $menu_slug)
    {
        $qb = $this->getQbMenus(false)
//            ->where('m.active = true ')
        ;

        if('accueil' != $sheet_slug){
            $qb
                ->andWhere('sheet.slug = :sheet_slug')
                ->setParameter('sheet_slug', $sheet_slug)
            ;
        }

        if(!in_array($menu_slug, ['accueil', 'contact'])){
            $qb
                ->andWhere('m.slug = :menu_slug')
                ->setParameter('menu_slug', $menu_slug)
                ;
        }
        $list = $qb
            ->getQuery()
            ->getResult()
        ;

        if(isset($list[0])){
            return $list[0];
        }
        if([] == $list){
            return null;
        }
        return $list;
    }

}
