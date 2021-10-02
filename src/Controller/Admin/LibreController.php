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
class LibreController extends AbstractController
{
    /**
     * @Route("/hms-libre/", name="add_page_hms_libre", methods={"GET|POST"})
     */
    public function newPageHmsLibre(Request $request): Response
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
                $this->addPageHmsLibre($libres);
                return $this->redirectToRoute('admin_index');
            }
        }

        return $this->render('admin/menu/new_libre_hms.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    private function addPageHmsLibre($libres){

        $config = $this->getActiveConfig();
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
        $this->addFlash('info', 'Page créée!');
        return $this->redirectToRoute('admin_index');
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
