<?php

namespace App\Controller\Admin;

use App\Entity\Config\Config;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AbstractAdminController extends AbstractController
{
    protected function mergeActiveConfig($array){

        $emConfig = $this->get('doctrine')->getManager('config');
        $configuration = $emConfig->getRepository(Config::class, 'config')->getActiveConfig();

        $array = array_merge($array, $configuration);

        return $array;
    }

}
