<?php

namespace App\Repository;

use App\Entity\Interfaces\ContactInterface;
use App\Entity\Hermes\Sheet;
use App\Repository\Traits\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

/**
 * @method Sheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sheet[]    findAll()
 * @method Sheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SheetRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sheet::class);
    }

    /**
     * @return int Returns max position value
     */
    public function getMaxPosition()
    {
        $qb = $this->getQbMaxPosition();

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






    public function getQbSheets()
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.active = true ')
            ->orderBy('s.locale', 'ASC')
            ->addOrderBy('s.position', 'ASC')
        ;

        return $qb;
    }

    public function getQbSheetsWithoutContact()
    {
            $qb = $this->getQbSheets()
                ->where('s.code != :contact ')
                ->setParameter(ContactInterface::CONTACT, ContactInterface::CONTACT)
            ;
        return $qb;
    }


    public function getSheetsWithoutContact()
    {
        try {
            $list = $this->getQbSheetsWithoutContact()
                ->getQuery()
                ->getResult()
            ;
        }catch (\Exception $e){
            return [];
        }

        return $list;
    }


    /**
     * @return int Returns nb locales
     */
    public function getLocales()
    {
        $qb = $this->getQBLocales();

        $locales = $qb
            ->getQuery()
            ->getResult();

        return $locales;
    }


    public function getQBLocales()
    {
        return $this->createQueryBuilder('s')
            ->select('s.locale')
            ->distinct()
            ;
    }



    /**
     * @return Sheet
     */
    public function getSheetSlugBySlugAndLocale($sheet_slug, $locale)
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.active = true ')
            ->andWhere('s.slug = :slug ')
            ->setParameter('slug', $sheet_slug)
        ;
        $sheet = $qb
            ->getQuery()
            ->getOneOrNullResult();

        if(is_null($sheet)){
            return $sheet_slug;
        }
        $referenceName = $sheet->getReferenceName();

        $sheet = $this->createQueryBuilder('s')
            ->where('s.active = true ')
            ->andWhere('s.referenceName = :referenceName ')
            ->andWhere('s.locale = :locale ')
            ->setParameter('referenceName', $referenceName)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult();

        if(is_null($sheet)){
            return $sheet_slug;
        }

        $sheet_slug= $sheet->getSlug();

        return $sheet_slug;
    }
    
    
    
    
    

}
