<?php

namespace App\Controller\Admin;

use App\Entity\Remote;
use App\Form\Admin\RemoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/remote")
 */
class RemoteController extends AbstractController
{
    /**
     * @Route("/", name="remote_index", methods={"GET"})
     */
    public function index(): Response
    {
        $remotes = $this->getDoctrine()
            ->getRepository(Remote::class)
            ->findAll();

        return $this->render('admin/remote/index.html.twig', [
            'remotes' => $remotes,
        ]);
    }

    /**
     * @Route("/new", name="remote_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $remote = new Remote();
        $form = $this->createForm(RemoteType::class, $remote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $remote->updateImages();
            $entityManager->persist($remote);
            $entityManager->flush();

            return $this->redirectToRoute('remote_index');
        }

        return $this->render('admin/remote/new.html.twig', [
            'remote' => $remote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="remote_show", methods={"GET"})
     */
    public function show(Remote $remote): Response
    {
        return $this->render('admin/remote/show.html.twig', [
            'remote' => $remote,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="remote_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Remote $remote): Response
    {
        $form = $this->createForm(RemoteType::class, $remote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $remote->updateImages();
            $this->getDoctrine()->getManager()->persist($remote);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('remote_index');
        }

        return $this->render('admin/remote/edit.html.twig', [
            'remote' => $remote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="remote_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Remote $remote): Response
    {
        if(!$this->isGranted('ROLE_SUPER_ADMIN')){
            $this->redirectToRoute('admin_index');
        }

        if ($this->isCsrfTokenValid('delete'.$remote->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($remote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('remote_index');
    }
}
