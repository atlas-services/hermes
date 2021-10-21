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
use App\Form\Admin\Libre\TemplateLibreHmsCollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/add/libre")
 */
class LibreController extends AbstractAdminController
{
    /**
     * @Route("/hms-libre/", name="add_page_hms_libre", methods={"GET|POST"})
     */
    public function onePageHmsLibre(Request $request): Response
    {

        $form = $this->createForm(TemplateLibreHmsCollectionType::class );
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            if ( $form->isValid()) {
                $templates = $request->request->get("template_libre_hms_collection")['templates'];
                foreach ($templates as $key => $template){
                    $libres[$key]['code'] = $this->getDoctrine()->getRepository(Template::class)->find($template['code'])->getCode();
                    $libres[$key]['name'] = $this->getDoctrine()->getRepository(Template::class)->find($template['code'])->getName();
                    $libres[$key]['name'] = $this->getDoctrine()->getRepository(Template::class)->find($template['code'])->getSummary();
                }
                //$this->addPageHmsLibre($libres);
                $this->addOnePageHmsLibre($libres);
                return $this->redirectToRoute('admin_index');
            }
        }

        $array = [
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/menu/new_libre_hms.html.twig', $array);

    }

    private function addPageHmsLibre($libres){

        $config = $this->getActiveConfig();
        $emConfig = $this->getDoctrine()->getManager('config');
        $entityManager = $this->getDoctrine()->getManager();

        $content = "";
        foreach ($libres as $template ){
            $template_libre = str_replace('é', 'e',str_replace(' ', '-', str_replace('\'', '-', $template['code'])));
            $content .= $this->render('admin/hermes/menu-libre/href.html.twig', ['template' => $template_libre])->getContent();
            $content .= $this->render('admin/hermes/template-libre/'.$template_libre.'/index.html.twig', $config)->getContent();
        }
        $menu_libre = $this->render('admin/hermes/menu-libre/hms-1.html.twig', ['titre' => 'libre', 'templates' => $libres])->getContent();

        $content = $menu_libre.$content;

        $libre = 'libre';
        $slug = strtolower($libre);

        try {
            $config_nav_bar =  $this->getDoctrine()
                ->getRepository(Config::class)
                ->findOneBy(['code'=> 'nav_bar']);
            $config_nav_bar->setValue('one page');

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
                $emConfig->persist($config_nav_bar);
            }

        }catch (\Exception $e){
            $this->addFlash('warning', $e->getMessage());
            return $this->redirectToRoute('admin_index');
        }
        $entityManager->flush();
        $emConfig->flush();
        $this->addFlash('info', 'Page créée!');
        return $this->redirectToRoute('admin_index');
    }

    private function addOnePageHmsLibre($libres){
        $config = $this->getActiveConfig();
        $emConfig = $this->getDoctrine()->getManager('config');
        $entityManager = $this->getDoctrine()->getManager();

        $libre = 'one-page';
        $slug = strtolower($libre);

        try {
            $config_nav_bar =  $this->getDoctrine()
                ->getRepository(Config::class)
                ->findOneBy(['code'=> 'nav_bar']);
            $config_nav_bar->setValue('one page');

            $sheet_code = 'new_sheet'.$libre;
            $sheet = $this->getDoctrine()
                ->getRepository(Sheet::class)
                ->findOneBy(['code'=> $sheet_code]);
            if(is_null($sheet)) {

                // add sheet
                $sheet = new Sheet();
                $sheet->setCode($sheet_code);
                $sheet->setName($libre);
                $sheet->setPosition(1);
                $sheet->setSummary('Menu ' . $libre);
                $sheet->setSlug($slug);

                // add menu
                $menu = new Menu();
                $menu->setCode($libre);
                $menu->setName($libre);
                $menu->setPosition(1);
                $menu->setSlug($slug);
                $menu->setSheet($sheet);

                $entityManager->persist($sheet);
                $entityManager->persist($menu);
            }else{
                $menu = $this->getDoctrine()
                    ->getRepository(Menu::class)
                    ->findOneBy(['code'=> $libre]);
            }

                $template = $this->getDoctrine()
                    ->getRepository(Template::class)
                    ->findOneBy(['code'=> 'libre']);
                $content = "";
                foreach ($libres as $key => $template_libre ){
                    $position = $key +1;
                    // add section
                    $section = new Section();
                    $section->setName('section-'.str_replace(' ', '-', $libre.$position));
                    $section->setPosition($position);
                    $section->setTemplateWidth(12);
                    $section->setMenu($menu);
                    $section->setTemplate($template);
                    // add Post
                    $template_libre = str_replace('é', 'e',str_replace(' ', '-', str_replace('\'', '-', $template_libre['code'])));
                    $content = $this->render('admin/hermes/menu-libre/href.html.twig', ['template' => $template_libre])->getContent();
                    $content .= $this->render('admin/hermes/template-libre/'.$template_libre.'/index.html.twig', $config)->getContent();

                    $post = new Post();
                    $post->setName($libre.$position);
                    $post->setPosition($position);
                    $post->setSection($section);
                    $post->setContent($content);

                    $entityManager->persist($section);
                    $entityManager->persist($post);
                }

                $section_name = 'section-'.str_replace(' ', '-', "menu-".$libre);
                $onePageSection = $this->getDoctrine()
                ->getRepository(Section::class)
                ->findOneBy(['name'=> $section_name]);
                if(is_null($onePageSection)){
                    $section = new Section();
                    $section->setName($section_name);
                    $section->setPosition(0);
                    $section->setTemplateWidth(12);
                    $section->setMenu($menu);
                    $section->setTemplate($template);


                    // add Post
                    $menu_libre = $this->render('admin/hermes/menu-libre/hms-1.html.twig', ['titre' => 'libre', 'templates' => $libres])->getContent();       $content = $this->render('admin/hermes/menu-libre/href.html.twig', ['template' => $template_libre])->getContent();
                    $post = new Post();
                    $post->setName("menu-".$libre);
                    $post->setPosition(0);
                    $post->setSection($section);
                    $post->setContent($menu_libre);

                    $entityManager->persist($section);
                    $entityManager->persist($post);
                }
                $emConfig->persist($config_nav_bar);
//            }

        }catch (\Exception $e){
            $this->addFlash('warning', $e->getMessage());
            return $this->redirectToRoute('admin_index');
        }
        $entityManager->flush();
        $emConfig->flush();
        $this->addFlash('info', 'Page créée!');
        return $this->redirectToRoute('admin_index');

    }

}
