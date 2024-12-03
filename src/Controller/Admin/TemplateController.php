<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\Template;
use App\Form\Admin\TemplateType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/{_locale}/admin/template')]
class TemplateController extends AbstractAdminController
{
    #[Route(path: '/', name: 'template_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $templates = $doctrine
            ->getRepository(Template::class)
            ->findAll();

        $array = [
            'templates' => $templates,
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/template/index.html.twig', $array);
    }

    #[Route(path: '/new', name: 'template_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $template = new Template();
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($template);
            $entityManager->flush();

            return $this->redirectToRoute('template_index');
        }

        return $this->render('admin/template/new.html.twig', [
            'template' => $template,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'template_show', methods: ['GET'])]
    public function show(Template $template): Response
    {
        return $this->render('admin/template/show.html.twig', [
            'template' => $template,
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'template_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ManagerRegistry $doctrine, Template $template): Response
    {
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('template_index');
        }

        $array = [
            'hms_template' => $template,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/template/edit.html.twig', $array );
    }

    #[Route(path: '/{id}', name: 'template_delete', methods: ['POST', 'DELETE'])]
    public function delete(Request $request, ManagerRegistry $doctrine, Template $template): Response
    {
        if(!$this->isGranted('ROLE_SUPER_ADMIN')){
            $this->redirectToRoute('admin_index');
        }

        if ($this->isCsrfTokenValid('delete'.$template->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($template);
            $entityManager->flush();
        }

        return $this->redirectToRoute('template_index');
    }
}
