<?php

namespace App\Controller\Admin;

use App\Entity\Config;
use App\Entity\Sheet;
use App\Form\Admin\SheetType;
use App\Repository\SheetRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @Route("/{_locale}/admin")
 */
class SheetController extends AbstractController
{
    /**
     * @Route("/page/", name="sheet_index", methods={"GET"})
     * @Route("/form/", name="sheet_form_index", methods={"GET"})
     */
    public function index(Request $request,SheetRepository $sheetRepository): Response
    {
        $route = $request->attributes->get('_route');
        $sheets = $sheetRepository->findAll();
        $config_form = $this->getDoctrine()->getManager()->getRepository(Config::class)->findOneBy(['active'=> true, 'code'=>'form']);

        if('sheet_index' == $route){
            return $this->render('admin/sheet/index.html.twig', [
                'sheets' => $sheets,
                'config' => $config_form,
            ]);
        }
        if('sheet_form_index' == $route){
            return $this->render('admin/sheet/form.html.twig', [
                'sheets' => $sheets,
                'config' => $config_form,
            ]);
        }
        return $this->render('admin/sheet/index.html.twig', [
            'sheets' => $sheets,
            'config' => $config_form,
        ]);
    }

    /**
     * @Route("/nouvelle-page", name="sheet_new", methods={"GET","POST"})
     */
    public function new(Request $request,SheetRepository $sheetRepository): Response
    {
        $position_sheet = $sheetRepository->getMaxPosition();
        $sheet = new Sheet();
        $sheet->setPosition($position_sheet);
        $form = $this->createForm(SheetType::class, $sheet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sheet);
            $entityManager->flush();
            if ($form->get('saveAndAdd')->isClicked()) {
                return $this->redirectToRoute('menu_section_post_new_sheet', ['sheet'=> $sheet->getSlug()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('sheet_index');
            }
        }

        return $this->render('admin/sheet/new.html.twig', [
            'sheet' => $sheet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/page/{id}", name="sheet_show", methods={"GET"})
     */
    public function show(Sheet $sheet): Response
    {
        return $this->render('admin/sheet/show.html.twig', [
            'sheet' => $sheet,
        ]);
    }

    /**
     * @Route("/page/edit/{sheet}", name="sheet_edit", methods={"GET","POST"})
     * @ParamConverter("sheet",class="App\Entity\Sheet", options={"mapping": {"sheet": "slug"}})
     */
    public function edit(Request $request, CacheInterface $backCache, Sheet $sheet): Response
    {

        $form = $this->createForm(SheetType::class, $sheet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $sheetslug = $sheet->getSlug();
            foreach ($sheet->getMenus() as $menu){
                $slug = $menu->getSlug();
                $backCache->delete('front_page_cache_key'.$sheetslug.$slug);
            }
            if ($form->get('saveAndAdd')->isClicked()) {
                $request->getSession()->set('sheet', $sheet);
                return $this->redirectToRoute('menu_section_post_new_sheet', ['sheet'=> $sheet->getSlug()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('sheet_index');
            }
            return $this->redirectToRoute('sheet_index');
        }

        return $this->render('admin/sheet/edit.html.twig', [
            'sheet' => $sheet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/page/{id}", name="sheet_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sheet $sheet): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sheet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sheet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sheet_index');
    }

    /**
     * @Route("/ajax/switch/sheet", name="switch_sheet_active_ajax")
     */
    public function ajaxActive(Request $request, SheetRepository $sheetRepository)
    {
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('id');
            $data = $sheetRepository->switchActive($id);
            return new JsonResponse(array('data' =>$data->isActive()));
        }

        return new Response('This is not ajax!', 400);
    }
}
