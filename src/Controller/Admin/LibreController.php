<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\Template;
use App\Form\Admin\Libre\PostLibreType;
use App\Service\Onepage;
use App\Form\Admin\Libre\TemplateLibreHmsCollectionType;
use App\Form\Admin\Libre\MenuLibreType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/add/libre")
 */
class LibreController extends AbstractAdminController
{
    /**
     * @Route("/one-page/hms-libre/", name="add_page_hms_libre", methods={"GET|POST"})
     */
    public function onePageHmsLibre(Request $request, Onepage $onepage): Response
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
                $configuration = $this->getActiveConfig();
                $message = $onepage->addOnePageHmsLibre($this->getDoctrine()->getManager('config'), $configuration, $libres);
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
     * @Route("/one-page/libre", name="add_onepage_libre", methods={"GET","POST"})
     */
    public function onePageLibre(Request $request, Onepage $onepage): Response
    {

        $post = $onepage->getPostOnePage(Template::TEMPLATE_LIBRE);

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

}
