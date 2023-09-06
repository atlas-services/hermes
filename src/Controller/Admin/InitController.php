<?php

namespace App\Controller\Admin;

use App\Entity\Config\Config;
use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use App\Entity\Hermes\Sheet;
use App\Entity\Hermes\Template;
use App\Entity\Hermes\User;
use App\Form\Admin\ConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin")
 */
class InitController extends AbstractAdminController
{
    /**
     * @Route("/init/media", name="init_media", methods={"GET"})
     */
    public function removeMedia(Filesystem $filesystem, ParameterBagInterface $params): Response
    {
        $image_dir = $params->get('hermes_path_content_images').'/*';
        $image_post_dir = $params->get('hermes_path_content_image_post').'/*';
        $filesystem->remove($image_dir, $image_post_dir);
        return $this->redirectToRoute('admin_index');

    }

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
                ->getRepository(Config::class, 'config')
                ->findAll();
            $dbtemplate = $this->getDoctrine()
                ->getRepository(Template::class)
                ->findAll();
            $this->addConfig($configurations, $dbuser, $dbconfig,$dbtemplate);
        }catch (\Exception $e){
            echo $e->getMessage();
            echo $e->getTraceAsString();

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
        $entityManagerConfig = $this->getDoctrine()->getManager('config');
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
                            $entityManagerConfig->persist($config);
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
        $entityManagerConfig->flush();
    }

    private function addPage($libre){
        $emConfig = $this->get('doctrine')->getManager('config');
        $config = $emConfig->getRepository(Config::class, 'config')->getActiveConfig();

        $entityManager = $this->getDoctrine()->getManager();
        $template_libre = str_replace('é', 'e',str_replace(' ', '-', str_replace('\'', '-', $libre)));
        $slug = strtolower($template_libre);
//        $content = $this->render('admin/hermes/template-libre/'.$template_libre.'.html.twig', $config)->getContent();
        $content = $this->render('admin/hermes/template-libre/'.$template_libre.'/index.html.twig', $config)->getContent();
        try {
            $sheet = $this->getDoctrine()
                ->getRepository(Sheet::class)
                ->findBy(['code'=> 'new_sheet'.$libre]);
            if(!$sheet){
                // add sheet
                $sheet = new Sheet();
                $sheet->setCode($libre);
                $sheet->setName($libre);
                $sheet->setPosition(1);
                $sheet->setSummary('Menu '.$libre);
                $sheet->setSlug($slug);
                // add menu
                $menu = new Menu();
                $menu->setCode($libre);
                $menu->setName($libre);
                $menu->setPosition(1);
                $menu->setSlug($slug);
                $menu->setSheet($sheet);
                // add section
                $template = $this->getDoctrine()
                    ->getRepository(Template::class)
                    ->findOneBy(['code'=> 'libre']);
                $section = new Section();
                $section->setName('section-'.str_replace(' ', '-', $libre));
                $section->setPosition(1);
                $section->setTemplateWidth(12);
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
        $this->addFlash('success', 'Page créée!');
        return $this->redirectToRoute('admin_index');
    }

}
