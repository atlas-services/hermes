<?php

namespace App\Repository;

use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Sheet;
use App\Entity\Interfaces\ContactInterface;
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

    public function getQbMenus($active = true, $locale ='fr')
    {
        $qb = $this->createQueryBuilder('m')
            ->join('m.sheet', 'sheet')
            ->andWhere('sheet.active = true ')
//            ->andWhere('sheet.locale = :locale ')
//            ->setParameter('locale', $locale)
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


    public function getMenusByLocale($locale)
    {
        try {
            $list = $this->getQbMenus(true, $locale)
                ->getQuery()
                ->getResult()
            ;

            if([] == $list){
                return [];
            }
            foreach ($list as $menu){
                if($locale == $menu->getSheet()->getLocale()){
                    $menus[$menu->getSheet()->getName()][$menu->getName()] = $menu;
                }
            }

            $list = $menus;

        }catch (\Exception $e){
            return [];
        }
        return $list;

    }
    public function getMenus()
    {
        try {
            $list = $this->getQbMenus(true)
                ->getQuery()
                ->getResult()
            ;
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

    public function getMyMenuBySheetAndMenuSlugs($sheet_slug, $menu_slug, $locale)
    {
        $qb = $this->getQbMenus(false)
//            ->where('m.active = true ')
        ;

        if('accueil' != $sheet_slug){
            $qb
                ->andWhere('sheet.slug = :sheet_slug')
                ->setParameter('sheet_slug', $sheet_slug)
                ->andWhere('sheet.locale = :locale')
                ->setParameter('locale', $locale)
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

//        if( [] == $list ){
//            $list = $this->getQbMenus()
//                ->andWhere('sheet.locale = :locale')
//                ->setParameter('locale', $locale)
//                ->getQuery()
//                ->getResult()
//            ;
//        }

        if(isset($list[0])){
            return $list[0];
        }
        if([] == $list){
            return null;
        }
        return $list;
    }

    /**
     * @return array Returns menu courant dans les locales
     */
    public function getLocalesByMenu($menu,$sheet)
    {
        $locales = [];
        if(ContactInterface::CONTACT == $sheet){
            $qb = $this->getQBLocalesContactBySlug($sheet);

            $menus = $qb
                ->getQuery()
                ->getResult();


            foreach ($menus as $menu){
                $locales[$menu->getLocale()] = [
                    'locale' => $menu->getLocale(),
                    'sheet' => $menu->getSlug(),
                    'slug' => $menu->getSlug()
                ] ;
            }
            return $locales;
        }

        if(!is_null($menu)){
            $referenceName = $menu->getReferenceName();

            $qb = $this->getQBLocalesByReferenceName($referenceName);

            $menus = $qb
                ->getQuery()
                ->getResult();

            foreach ($menus as $menu){
                $locales[$menu->getLocale()] = [
                    'locale' => $menu->getLocale(),
                    'sheet' => $menu->getSheet()->getSlug(),
                    'slug' => $menu->getSlug()
                ] ;
            }

        }


        return $locales;
    }


    public function getQBLocalesByReferenceName($referenceName)
    {

        return $this->createQueryBuilder('m')
            ->andWhere('m.referenceName = :referenceName ')
            ->setParameter('referenceName', $referenceName)
            ;
    }


    public function getQBLocalesContactBySlug($slug)
    {
        return $this->getEntityManager()->getRepository(Sheet::class)->createQueryBuilder('s')
            ->andWhere('s.slug = :slug ')
            ->setParameter('slug', $slug)
            ;
    }


}
