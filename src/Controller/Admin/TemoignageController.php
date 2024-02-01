<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\Temoignage;
use App\Form\Admin\TemoignageType;
use App\Repository\TemoignageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin")
 */
class TemoignageController extends AbstractAdminController
{
    /**
     * @Route("/temoignage/", name="temoignage_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $temoignages = $doctrine
            ->getRepository(Temoignage::class)
            ->findAll();
        $array = [
            'temoignages' => $temoignages,
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/temoignage/index.html.twig', $array);
    }

    /**
     * @Route("/edit/{id}", name="temoignage_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ManagerRegistry $doctrine, Temoignage $temoignage): Response
    {

        $form = $this->createForm(TemoignageType::class, $temoignage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('temoignage_index');
        }

        return $this->render('admin/temoignage/edit.html.twig', [
            'temoignage' => $temoignage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/temoignage/{id}", name="temoignage_show", methods={"GET"})
     */
    public function show(Temoignage $temoignage): Response
    {
        return $this->render('admin/temoignage/show.html.twig', [
            'temoignage' => $temoignage,
        ]);
    }


    /**
     * @Route("/temoignage/{id}", name="temoignage_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ManagerRegistry $doctrine, Temoignage $temoignage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$temoignage->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($temoignage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('temoignage_index');
    }

    /**
     * @Route("/ajax/switch/temoignage", name="switch_temoignage_active_ajax")
     */
    public function ajaxActive(Request $request, TemoignageRepository $temoignageRepository)
    {
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('id');
            $data = $temoignageRepository->switchActive($id);
            return new JsonResponse(array('data' =>$data->isActive()));
        }

        return new Response('This is not ajax!', 400);
    }
}
