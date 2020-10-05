<?php

namespace App\Controller\Admin;

use App\Entity\Config;
use App\Entity\Menu;
use App\Entity\Sheet;
use App\Entity\Template;
use App\Entity\User;
use App\Form\Admin\ConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/add-config")
 */
class InitController extends AbstractController
{
    /**
     * @Route("/", name="add_config", methods={"GET"})
     */
    public function new(): Response
    {
        $configurations = $this->getParameter('init');
        try {
            $dbuser = $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll();
            $dbconfig = $this->getDoctrine()
                ->getRepository(Config::class)
                ->findAll();
            $dbtemplate = $this->getDoctrine()
                ->getRepository(Template::class)
                ->findAll();
            $this->newConfig($configurations, $dbuser, $dbconfig,$dbtemplate);
        }catch (\Exception $e){

        }
        return $this->redirectToRoute('app_login');

    }

    private function newConfig($configurations, $dbuser,$dbconfig,$dbtemplate){
        $entityManager = $this->getDoctrine()->getManager();
        $dbuseremail = array_column($dbuser, 'email');
        $dbconfigcode = array_column($dbconfig, 'code');
        $dbtemplatecode = array_column($dbtemplate, 'code');

        foreach ($configurations as $key=>$value){
            if('user' == $key){
                foreach ($value as $type=>$configuration){
                    foreach ($configuration as $code=>$conf){
                        if('email' == $code){
                            if(!in_array($conf, $dbuseremail)){
                                $user = new User();
                                $user->setFirstname($configuration['firstname']);
                                $user->setLastname($configuration['lastname']);
                                $user->setEmail($configuration['email']);
                                $user->setPassword($configuration['password']);
                                $user->setRoles([$configuration['roles']]);
                                $entityManager->persist($user);
                            }
                        }
                    }
                }
            }
            if('config' == $key){
                foreach ($value as $type=>$configuration){
                    foreach ($configuration as $code=>$conf){
                        if(!in_array($code, $dbconfigcode)){
                            $config = new Config();
                            $config->setType($type);
                            $config->setCode($code);
                            $config->setSummary($conf['summary']);
                            $config->setValue($conf['value']);
                            $entityManager->persist($config);
                        }
                    }
                }
            }
            if('template' == $key){
                foreach ($value as $code=>$conf){
                    if(!in_array($code, $dbtemplatecode)){
                        $template = new Template();
                        $template->setCode($code);
                        $template->setSummary($conf['summary']);
                        $template->setName($conf['name']);
                        $entityManager->persist($template);
                    }
                }
            }
        }
        $entityManager->flush();
    }


}
