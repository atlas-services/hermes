<?php

namespace App\Controller\Admin;

use App\Entity\Config\Config;
use App\Entity\Hermes\Sheet;
use App\Form\Admin\LocaleType;
use App\Form\Admin\SheetType;
use App\Form\Admin\Libre\SheetLibreType;
use App\Form\Admin\Liste\SheetListeType;
use App\Repository\SheetRepository;
use App\Service\Copy;
use App\Service\Image;
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
class SheetController extends AbstractAdminController
{
    /**
     * @Route("/page/", name="sheet_index", methods={"GET"})
     * @Route("/form/", name="sheet_form_index", methods={"GET"})
     */
    public function index(Request $request,SheetRepository $sheetRepository): Response
    {
        $route = $request->attributes->get('_route');
        $sheets = $sheetRepository->findAll();
        $config_form = $this->getDoctrine()->getRepository(Config::class, 'config')->findOneBy(['active'=> true, 'code'=>'forms']);

        $array = [
            'sheets' => $sheets,
            'config' => $config_form,
        ];
        $array = $this->mergeActiveConfig($array);

        if('sheet_index' == $route){
            return $this->render('admin/sheet/index.html.twig', $array);
        }
        if('sheet_form_index' == $route){
            return $this->render('admin/sheet/form.html.twig', $array);
        }
        return $this->render('admin/sheet/index.html.twig', $array);
    }

    /**
     * @Route("/nouvelle-page", name="sheet_new", methods={"GET","POST"})
     */
    public function new(Request $request,SheetRepository $sheetRepository, Copy $copy): Response
    {
        $position_sheet = $sheetRepository->getMaxPosition();
        $sheet = new Sheet();
        $sheet->setPosition($position_sheet);
        $form = $this->createForm(SheetType::class, $sheet, ['position' => $position_sheet]);
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
            // if ($form->get('saveAndAddHermesListe')->isClicked()) {
            //     $copy->handleHermesDir($sheet);
            //     return $this->redirectToRoute('sheet_index');
            // }
        }

        $array = [
            'sheet' => $sheet,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/sheet/new.html.twig', $array);
    }

    /**
     * @Route("/nouvelle-page_libre", name="sheet_new_libre", methods={"GET","POST"})
     */
    public function newLibre(Request $request,SheetRepository $sheetRepository): Response
    {
        $position_sheet = $sheetRepository->getMaxPosition();
        $sheet = new Sheet();
        $sheet->setPosition($position_sheet);
        $form = $this->createForm(SheetLibreType::class, $sheet,['saveLibre' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sheet);
            $entityManager->flush();
            if ($form->get('saveLibre')->isClicked()) {
                return $this->redirectToRoute('menu_section_post_new_sheet_libre', ['sheet'=> $sheet->getSlug()]);
            }
        }

        $array = [
            'sheet' => $sheet,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($array);
        return $this->render('admin/sheet/new_libre.html.twig', $array);
    }

    /**
     * @Route("/nouvelle-page_liste", name="sheet_new_liste", methods={"GET","POST"})
     */
    public function newListe(Request $request,SheetRepository $sheetRepository): Response
    {
        $position_sheet = $sheetRepository->getMaxPosition();
        $sheet = new Sheet();
        $sheet->setPosition($position_sheet);
        $form = $this->createForm(SheetListeType::class, $sheet,['saveListe' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sheet);
            $entityManager->flush();
            if ($form->get('saveListe')->isClicked()) {
                return $this->redirectToRoute('menu_section_post_new_sheet_liste', ['sheet'=> $sheet->getSlug()]);
            }
        }

        $array = [
            'sheet' => $sheet,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/sheet/new_liste.html.twig', $array);
    }

    /**
     * @Route("/page/{id}", name="sheet_show", methods={"GET"})
     */
    public function show(Sheet $sheet): Response
    {
        $array = [
            'sheet' => $sheet,
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/sheet/show.html.twig', $array);
    }

    /**
     * @Route("/page/edit/{sheet}/{locale}", name="sheet_edit", methods={"GET","POST"})
     * @ParamConverter("sheet",class="App\Entity\Hermes\Sheet", options={"mapping": {"sheet": "slug", "locale": "locale"}})
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

        $array = [
            'sheet' => $sheet,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/sheet/edit.html.twig', $array);
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


    /**
     * @Route("/nouvelle-langue", name="sheet_locale_new", methods={"GET","POST"})
     */
    public function newLocale(Request $request, Copy $copy): Response
    {
        $form = $this->createForm(LocaleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $locale = $request->request->get('locale')['locale'];
            $copy->copyLocale($locale);
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('admin_index');
            }
        }

        $array = [
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/sheet/newLocale.html.twig', $array);
    }



}
