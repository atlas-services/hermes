<?php

namespace App\Controller\Admin;

use App\Entity\Config;
use App\Entity\Menu;
use App\Entity\Post;
use App\Entity\Section;
use App\Entity\Sheet;
use App\Entity\Template;
use App\Entity\User;
use App\Form\Admin\ConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/add")
 */
class InitController extends AbstractController
{
    /**
     * @Route("/config/", name="add_config", methods={"GET"})
     */
    public function newConfig(): Response
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
            $this->addConfig($configurations, $dbuser, $dbconfig,$dbtemplate);
        }catch (\Exception $e){

        }
        return $this->redirectToRoute('admin_index');

    }

    /**
     * @Route("/page/{libre}", name="add_page", methods={"GET"})
     */
    public function newPage($libre): Response
    {

        $this->addPage($libre);
        return $this->redirectToRoute('admin_index');

    }

    private function addConfig($configurations, $dbuser,$dbconfig,$dbtemplate){
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

    private function addPage($libre){
        $entityManager = $this->getDoctrine()->getManager();

        $content = $this->render('admin/exemple/base/'.$libre.'.html.twig', [])->getContent();
        try {
            $sheet = $this->getDoctrine()
                ->getRepository(Sheet::class)
                ->findBy(['code'=> 'new_sheet'.$libre]);
            if(!$sheet){
                // add sheet
                $sheet = new Sheet();
                $sheet->setCode($libre);
                $sheet->setName(str_replace('-', ' ', $libre));
                $sheet->setPosition(1);
                $sheet->setSummary('Menu '.$libre);
                $sheet->setSlug($libre);
                // add menu
                $menu = new Menu();
                $menu->setCode($libre);
                $menu->setName(str_replace('-', ' ', $libre));
                $menu->setPosition(1);
                $menu->setSlug($libre);
                $menu->setSheet($sheet);
                // add section
                $template = $this->getDoctrine()
                    ->getRepository(Template::class)
                    ->findOneBy(['code'=> 'libre']);
                $section = new Section();
                $section->setName('section-'.$libre);
                $section->setPosition(1);
                $section->setMenu($menu);
                $section->setTemplate($template);
                // add Post
                $post = new Post();
                $post->setName($libre);
                $post->setPosition(1);
                $post->setSection($section);
                $post->setContent($content);

                $entityManager->persist($sheet);
                $entityManager->persist($menu);
                $entityManager->persist($section);
                $entityManager->persist($post);
            }

        }catch (\Exception $e){
            $this->addFlash('warning', $e->getMessage());
            return $this->redirectToRoute('admin_index');
        }
        $entityManager->flush();
        $this->addFlash('info', 'Page créée!');
        return $this->redirectToRoute('admin_index');
    }


}
