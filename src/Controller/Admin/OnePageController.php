<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\Template;
use App\Form\Admin\Libre\PostLibreType;
use App\Form\Admin\Liste\PostListeType;
use App\Service\Onepage;
use App\Form\Admin\Libre\TemplateLibreHmsCollectionType;
use App\Form\Admin\Libre\MenuLibreType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/one-page")
 */
class OnePageController extends AbstractAdminController
{
    /**
     * @Route("/libre-hms/", name="add_onepage_libre_hms", methods={"GET|POST"})
     */
    public function onePageHmsLibre(Request $request, Onepage $onepage): Response
    {
        $onepage-> setOnePageConfig();

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

                $message = $onepage->addOnePageHmsLibre($libres);
                $this->addFlash(array_keys($message)[0], $message[array_keys($message)[0]]);
                return $this->redirectToRoute('admin_index');
            }
        }

        $array = [
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/menu/new_libre_hms.html.twig', $array);

    }

    /**
     * @Route("/libre", name="add_onepage_libre", methods={"GET","POST"})
     */
    public function onePageLibre(Request $request, Onepage $onepage): Response
    {
        $post = $onepage->getOnePagePostByTemplateType(Template::TEMPLATE_LIBRE);

        $form = $this->createForm(PostLibreType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post->getSection());
            $entityManager->persist($post->getSection()->getMenu());
            $entityManager->persist($post->getSection()->getMenu()->getSheet());
            $entityManager->persist($post);
            $entityManager->flush();
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('add_onepage_libre');
            }
            return $this->redirectToRoute('add_onepage_libre');
        }

        $array = [
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/post/new_libre.html.twig', $array);
    }

    /**
     * @Route("/liste", name="add_onepage_liste", methods={"GET","POST"})
     */
    public function onePageListe(Request $request, Onepage $onepage): Response
    {

        $post = $onepage->getOnePagePostByTemplateType(Template::TEMPLATE_LISTE);
        $section = $post->getSection();
        $menu = $section->getMenu();

        $options['saveAndAdd'] = true;
        $form = $this->createForm(PostListeType::class, $post, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('menu_index');
            }
            if ($form->get('saveListe')->isClicked()) {
                return $this->redirectToRoute('menu_index');
            }
            if ($form->get('saveAndAddPost')->isClicked()) {
//                return $this->redirectToRoute('section_post_new_menu', ['menu'=> $menu->getSlug(), 'section'=> $section->getId()]);
                return $this->redirectToRoute('post_new_section_liste', ['section'=> $section->getId()]);
            }
            return $this->redirectToRoute('menu_index');
        }

        $array = [
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/post/new_liste.html.twig', $array);
    }

    /**
     * @Route("/show/libre-hms/", name="show_onepage_libre_hms", methods={"GET|POST"})
     */
    public function showOnePageHmsLibre(Request $request, Onepage $onepage): Response
    {
        $configurations = $this->getParameter('init');

        foreach ($configurations as $key=>$value){
            if('template' == $key ){
                foreach ($value as $code => $template){
                    if( 'hms-' == substr($code, 0, 4) ){
                        $array['sections'][] = $template;
                    }
                }
            }
        }

        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/menu/show_libre_hms.html.twig', $array);

    }


}
