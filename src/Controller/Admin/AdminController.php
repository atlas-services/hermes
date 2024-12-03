<?php

namespace App\Controller\Admin;

use App\Entity\Config\Config;
use App\Entity\Hermes\Sheet;
use App\Service\Image;
use App\Service\TemplateLibreHermes;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/{_locale}/admin')]
class AdminController extends AbstractAdminController
{

    #[Route(path: '/', name: 'admin_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine, Image $image,TemplateLibreHermes $templateLibreHermes): Response
    {
        $image->shuffle();

        $sheets = $doctrine
            ->getRepository(Sheet::class)
            ->findAll();
        $listSheets='';
        foreach ($sheets as $key=> $sheet){
            $ponctuation = ', ';
            if($key === array_key_last($sheets)){
                $ponctuation = '.';
            }
            $listSheets .= $sheet->getName() . $ponctuation;
        }

        $array = ['sheets'=> $listSheets];
        $array = $this->mergeActiveConfig($doctrine, $array);

        $configurations = $this->getParameter('init');

        $arraySection = $templateLibreHermes->getList($configurations);
//        dd($arraySection);
        $array = array_merge($array, $arraySection);
//        foreach ($configurations as $key=>$value){
//            if('template' == $key ){
//                $entetes = $value['entete'];
//                foreach ($value as $code => $template){
//                    if( 'hms-' == substr($code, 0, 4) ){
//                        $array['sections'][] = $template;
//                    }
//                }
//            }
//        }
//        dd($array['sections']);

        return $this->render('admin/index.html.twig', $array);
    }

    #[Route(path: '/presentation', name: 'admin_presentation', methods: ['GET'])]
    public function presentation(): Response
    {

        $array = [];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/presentation.html.twig', $array);
    }

}
