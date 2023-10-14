<?php

namespace App\Repository;

use App\Entity\Hermes\Template;
use App\Repository\Traits\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

/**
 * @method Template|null find($id, $lockMode = null, $lockVersion = null)
 * @method Template|null findOneBy(array $criteria, array $orderBy = null)
 * @method Template[]    findAll()
 * @method Template[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    const TEMPLATES_BASE = [
    'libre' => 'libre', 
    'folio1' => 'folio1' ,
    'contact' => 'contact', 
    'newsletter' => 'newsletter',
    'livredor1' => 'livredor1', 
    'newsletter_template' => 'newsletter_template'
 ];

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Template::class);
    }


    public function getQbTemplates()
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.active = true ')
            ->andWhere("s.code not like '%-hms-%' " )
            ->orderBy('s.name', 'ASC')
        ;

        return $qb;
    }


    public function getQbTemplateByType($type, $active_form = true)
    {
        if(!is_null($type)){
            $qb = $this->createQueryBuilder('s')
            ->where('s.type = :type ' )
            ->setParameter('type',  $type )
        ;     
        }else{
            $qb = $this->getQbInitTemplates($active_form);
        }
        
        return $qb;
    }

    public function getTemplates()
    {
        $result = $this->getQbTemplates()
            ->getQuery()
            ->getResult()
        ;
        return $result;
    }

    public function getQbInitTemplates($active_form = true)
    {

        // il y a déja un formulaire
        $template_base = self::TEMPLATES_BASE ;        
        if(false == $active_form){
            unset($template_base['contact']);
            unset($template_base['newsletter']); 
            unset($template_base['livredor']); 
        }

        $qb = $this->getQbTemplates()
            ->where('s.active = true ')
            ->andWhere('s.code in (:code) ' )
            ->setParameter('code',  $template_base)
            ->orderBy('s.id', 'ASC')
        ;
        return $qb;
    }
    public function getQbTemplate2()
    {
        $qb = $this->getQbTemplates()
            ->where('s.code LIKE :modale ' )
            ->setParameter('modale',   '%modale%')
            ->orderBy('s.id', 'ASC')
        ;
        return $qb;
    }

    public function getQbTemplateLibre()
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.type = :libre ' )
            ->setParameter('libre',  'libre' )
        ;

        return $qb;
    }

    public function getTemplateLibre()
    {
        $result = $this->getQbTemplateLibre()
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result;
    }


    public function getQbTemplateListe()
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.code = :libre ' )
            ->setParameter('libre',  'folio1' )
        ;

        return $qb;
    }


    public function getQbTemplateLibreHms()
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.code like :libre ' )
            ->setParameter('libre',  '%hms%' )
        ;

        return $qb;
    }


}
