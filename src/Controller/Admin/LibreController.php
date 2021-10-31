<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\Template;
use App\Service\Onepage;
use App\Form\Admin\Libre\TemplateLibreHmsCollectionType;
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

}
