<?php

namespace App\Controller\Admin;

use App\Entity\Config\Config;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AbstractAdminController extends AbstractController
{
    protected function mergeActiveConfig($array){

        $configuration = $this->getActiveConfig();
        $array = array_merge($array, $configuration);

        return $array;
    }

    protected function getActiveConfig()
    {
        $config_simple=[];
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
