<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use App\Entity\Hermes\Sheet;
use App\Form\Admin\BaseMenuType;
use App\Form\Admin\MenuType;
use App\Form\Admin\Libre\MenuLibreType;
use App\Form\Admin\Liste\MenuListeType;
use App\Form\Admin\SectionType;
use App\Repository\MenuRepository;
use App\Repository\PostRepository;
use App\Repository\TemplateRepository;
use PHPUnit\Runner\Exception;
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
class MenuController extends AbstractController
{
    /**
     * @Route("/menu/", name="menu_index", methods={"GET"})
     */
    public function index(): Response
    {
        $menus = $this->getDoctrine()
            ->getRepository(Menu::class)
            ->findAll();

        return $this->render('admin/menu/index.html.twig', [
            'menus' => $menus,
        ]);
    }

    /**
     * @Route("/page/{sheet}/nouveau-menu/nouveau-contenu-libre", name="menu_section_post_new_sheet_libre", methods={"GET","POST"})
     * @ParamConverter("sheet",class="App\Entity\Hermes\Sheet", options={"mapping": {"sheet": "slug"}})
     */
    public function menuSectionPostNewSheetLibre(Request $request, ?Sheet $sheet, MenuRepository $menuRepository, TemplateRepository $templateRepository, PostRepository $postRepository): Response
    {

        $menu = new Menu();
        if(isset($sheet)){
            $menu->setName('page '. $sheet->getName());
        }
        $section= new Section();

        $template = $templateRepository->findOneBy(['code' => 'libre']);
        $section->setTemplate($template);
        $options['saveLibre'] = true;

        $post = new Post();
        $post->setName('contenu '. $menu->getName());
        $position_post = $postRepository->getMaxPosition($section);
        $post->setPosition($position_post);
        $section->addPost($post);
        $menu->addSection($section);
        $options['sheet'] = true;
        if(!is_null($sheet)) {
            $menu->setSheet($sheet);
            $options['sheet'] = false;
        }
        $form = $this->createForm(MenuLibreType::class, $menu,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $position_menu = $menuRepository->getMaxPosition($sheet);
            $menu->setPosition($position_menu);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($menu);
            $entityManager->flush();
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('menu_index');
            }
            if ($form->get('saveLibre')->isClicked()) {
                return $this->redirectToRoute('menu_index');
            }
            return $this->redirectToRoute('menu_index');
        }

        return $this->render('admin/menu/new_libre.html.twig', [
            'menu' => $menu,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/page/{sheet}/nouveau-menu/nouveau-contenu-liste", name="menu_section_post_new_sheet_liste", methods={"GET","POST"})
     * @ParamConverter("sheet",class="App\Entity\Hermes\Sheet", options={"mapping": {"sheet": "slug"}})
     */
    public function menuSectionPostNewSheetListe(Request $request, ?Sheet $sheet, MenuRepository $menuRepository, TemplateRepository $templateRepository, PostRepository $postRepository): Response
    {

        $menu = new Menu();
        if(isset($sheet)){
            $menu->setName( $sheet->getName());
        }
        $section= new Section();

        $template = $templateRepository->findOneBy(['code' => 'folio1']);
        $section->setTemplate($template);
        $options['saveListe'] = true;

        $post = new Post();
        $post->setName('contenu '. $menu->getName());
        $position_post = $postRepository->getMaxPosition($section);
        $post->setPosition($position_post);
        $section->addPost($post);
        $menu->addSection($section);
        $options['sheet'] = true;
        if(!is_null($sheet)) {
            $menu->setSheet($sheet);
            $options['sheet'] = false;
        }
        $form = $this->createForm(MenuListeType::class, $menu,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $position_menu = $menuRepository->getMaxPosition($sheet);
            $menu->setPosition($position_menu);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($menu);
            $entityManager->flush();
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('menu_index');
            }
            if ($form->get('saveListe')->isClicked()) {
                return $this->redirectToRoute('menu_index');
            }
            if ($form->get('saveAndAddPost')->isClicked()) {
                $section= $menu->getSections()[0];
                return $this->redirectToRoute('post_new_section_liste', ['section'=> $section->getId()]);
            }
            return $this->redirectToRoute('menu_index');
        }

        return $this->render('admin/menu/new_liste.html.twig', [
            'menu' => $menu,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/page/{sheet}/nouveau-menu/nouveau-contenu", name="menu_section_post_new_sheet", methods={"GET","POST"})
     * @ParamConverter("sheet",class="App\Entity\Hermes\Sheet", options={"mapping": {"sheet": "slug"}})
     */
    public function menuSectionPostNewSheet(Request $request, ?Sheet $sheet, MenuRepository $menuRepository, PostRepository $postRepository): Response
    {

        $menu = new Menu();
        if(isset($sheet)){
            $menu->setName('page '. $sheet->getName());
        }
        $section= new Section();
        $post = new Post();
        $post->setName('contenu '. $menu->getName());
        $position_post = $postRepository->getMaxPosition($section);
        $post->setPosition($position_post);
        $section->addPost($post);
        $menu->addSection($section);
        $options['sheet'] = true;
        $options['full_template'] = true;
        if(!is_null($sheet)) {
            $menu->setSheet($sheet);
            $options['sheet'] = false;
        }
        $form = $this->createForm(MenuType::class, $menu,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $position_menu = $menuRepository->getMaxPosition($sheet);
            $menu->setPosition($position_menu);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($menu);
            $entityManager->flush();
            if ($form->get('saveAndAddPost')->isClicked()) {
                $section= $menu->getSections()[0];
                return $this->redirectToRoute('post_new_section', ['section'=> $section->getId()]);
            }
            if ($form->get('saveAndAddSectionPost')->isClicked()) {
                $section= $menu->getSections()[0];
                return $this->redirectToRoute('section_post_new_menu', ['menu'=> $menu->getSlug(), 'section'=> $section->getId()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('menu_index');
            }
            return $this->redirectToRoute('menu_index');
        }

        return $this->render('admin/menu/new.html.twig', [
            'menu' => $menu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/menu/{id}", name="menu_show", methods={"GET"})
     */
    public function show(Menu $menu): Response
    {
        return $this->render('admin/menu/show.html.twig', [
            'menu' => $menu,
        ]);
    }

    /**
     * @Route("/page/{sheet}/menu/{menu}", name="menu_edit", methods={"GET","POST"})
     * @ParamConverter("sheet",class="App\Entity\Hermes\Sheet", options={"mapping": {"sheet": "slug"}})
     * @ParamConverter("menu",class="App\Entity\Hermes\Menu", options={"mapping": {"menu": "slug"}})
     */
    public function edit(Request $request, CacheInterface $backCache,Sheet $sheet, Menu $menu, MenuRepository $menuRepository): Response
    {
//        Le menun'est pas unique pour un slug donné, aussi il faut le récupérer avec le slug menu et le sheet
        $menu = $menuRepository->findOneBy(['slug'=> $menu->getSlug(), 'sheet'=>$sheet]);
        $form = $this->createForm(BaseMenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();

            $sheet = $menu->getSheet()->getSlug();
            $slug = $menu->getSlug();
            $backCache->delete('front_page_cache_key'.$sheet.$slug);
            if ($form->get('saveAndAdd')->isClicked()) {
                return $this->redirectToRoute('menu_section_post_new_sheet', ['sheet'=> $menu->getSheet()->getSlug()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('menu_index');
            }
            return $this->redirectToRoute('menu_index');
        }

        return $this->render('admin/menu/edit.html.twig', [
            'menu' => $menu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/menu/{id}/modele/{section}", name="menu_section_edit", methods={"GET","POST"})
     * @ParamConverter("section", class="App\Entity\Hermes\Section")
     */
    public function editSection(Request $request, Menu $menu, ?Section $section): Response
    {

        if(is_null($section)){
            $section = clone $menu->getSections()[0];
            foreach ($section->getPosts() as $post){
                $section->removePost($post);
            }
            $post = new Post();
            $section->addPost($post);
        }
        $form = $this->createForm(SectionType::class, $section);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($section);
            $em->flush();

            return $this->redirectToRoute('menu_index');
        }

        return $this->render('admin/section/edit.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/menu/{id}", name="menu_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Menu $menu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menu->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($menu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('menu_index');
    }

    /**
     * @Route("/ajax/switch/menu", name="switch_menu_active_ajax")
     */
    public function ajaxActive(Request $request, MenuRepository $menuRepository)
    {
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('id');
            $data = $menuRepository->switchActive($id);
            return new JsonResponse(array('data' =>$data->isActive()));
        }

        return new Response('This is not ajax!', 400);
    }
}
