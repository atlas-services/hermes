<?php

namespace App\Controller\Admin;

use App\Entity\Config\Config;
use App\Entity\Hermes\Sheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function index(): Response
    {
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
        $configuration = $this->getActiveConfig();

        $array = array_merge(['sheets'=> $listSheets], $configuration);

        return $this->render('admin/index.html.twig', $array);
    }

    /**
     * @Route("/presentation", name="admin_presentation", methods={"GET"})
     */
    public function presentation(): Response
    {

        $array = [];

        return $this->render('admin/presentation.html.twig', $array);
    }

    private function getActiveConfig()
    {
        /*
         * On récupère la configuration du site.
         */
        $entityManager = $this->getDoctrine()->getManager('config');
        $configuration = $entityManager->getRepository(Config::class, 'config')->findBy(['active' => true]);
        foreach ($configuration as $conf) {
            $config[$conf->getCode()] = $conf;
            if('bg_image' != $conf->getCode() && 'favicon' != $conf->getCode() && 'accueil' != $conf->getCode() && 'logo' != $conf->getCode()){
                $config_simple[$conf->getCode()] = $conf->getValue();
            }else{
                $config_simple[$conf->getCode()] = $conf;
            }
        }
        return $config_simple ;
    }

}
