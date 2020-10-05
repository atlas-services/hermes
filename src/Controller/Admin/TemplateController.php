<?php

namespace App\Controller\Admin;

use App\Entity\Template;
use App\Form\Admin\TemplateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/template")
 */
class TemplateController extends AbstractController
{
    /**
     * @Route("/", name="template_index", methods={"GET"})
     */
    public function index(): Response
    {
        $templates = $this->getDoctrine()
            ->getRepository(Template::class)
            ->findAll();

        return $this->render('admin/template/index.html.twig', [
            'templates' => $templates,
        ]);
    }

    /**
     * @Route("/new", name="template_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $template = new Template();
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($template);
            $entityManager->flush();

            return $this->redirectToRoute('template_index');
        }

        return $this->render('admin/template/new.html.twig', [
            'template' => $template,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="template_show", methods={"GET"})
     */
    public function show(Template $template): Response
    {
        return $this->render('admin/template/show.html.twig', [
            'template' => $template,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="template_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Template $template): Response
    {
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('template_index');
        }

        return $this->render('admin/template/edit.html.twig', [
            'template' => $template,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="template_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Template $template): Response
    {
        if(!$this->isGranted('ROLE_SUPER_ADMIN')){
            $this->redirectToRoute('admin_index');
        }

        if ($this->isCsrfTokenValid('delete'.$template->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($template);
            $entityManager->flush();
        }

        return $this->redirectToRoute('template_index');
    }
}
