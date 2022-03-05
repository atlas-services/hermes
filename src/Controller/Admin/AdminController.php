<?php

namespace App\Controller\Admin;

use App\Entity\Config\Config;
use App\Entity\Hermes\Sheet;
use App\Service\Image;
use App\Service\TemplateLibreHermes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin")
 */
class AdminController extends AbstractAdminController
{

    /**
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function index(Image $image,TemplateLibreHermes $templateLibreHermes): Response
    {
        $image->shuffle();

        $sheets = $this->getDoctrine()
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
        $array = $this->mergeActiveConfig($array);

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

    /**
     * @Route("/presentation", name="admin_presentation", methods={"GET"})
     */
    public function presentation(): Response
    {

        $array = [];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/presentation.html.twig', $array);
    }

}
