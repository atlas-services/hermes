<?php

namespace App\Controller\Admin;

use App\Api\ApiClient;
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
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Runner\Exception;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @Route("/{_locale}/admin")
 */
class MenuController extends AbstractAdminController
{
    /**
     * @Route("/menu/{sheet}", name="menu_index", defaults={"sheet": "All"},  methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine, $sheet): Response
    {
        $menus = $doctrine
        ->getRepository(Menu::class)
        ->findAll();
        if('All' === $sheet){
        $sheets = $doctrine
            ->getRepository(Sheet::class)
            ->findAll();
        }else{
            $menus = $doctrine
            ->getRepository(Menu::class)
            ->findBy(['sheet' => $sheet]);
            $sheets = $doctrine
            ->getRepository(Sheet::class)
            ->findBy(['id' => $sheet]);
        }

        $array = [
            'menus' => $menus,
            'sheets' => $sheets,
            'id' => $sheet
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);
        return $this->render('admin/menu/index.html.twig', $array);
    }


    /**
     * @Route("/add/menu/new", name="menu_new", methods={"GET","POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine, MenuRepository $menuRepository): Response
    {
//        Le menun'est pas unique pour un slug donné, aussi il faut le récupérer avec le slug menu et le sheet
        $menu = new Menu();
        $options = ['referenceName' => false];
        $options['label_name']= 'global.menu';
        $form = $this->createForm(BaseMenuType::class, $menu, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $last = $em->getRepository(Menu::class)->getMaxPosition($menu->getSheet());
            $menu->setPosition($last);
            $em->persist($menu);
            $em->flush();

            if ($form->get('saveAndAdd')->isClicked()) {
                return $this->redirectToRoute('menu_section_post_new_sheet', ['sheet'=> $menu->getSheet()->getSlug()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('menu_index');
            }
            return $this->redirectToRoute('menu_index');
        }

        $array = [
            'menu' => $menu,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/menu/new.html.twig', $array);
    }


    /**
     * @Route("/page/{sheet}/nouveau-menu/nouveau-contenu-libre", name="menu_section_post_new_sheet_libre", methods={"GET","POST"})
     */
    public function menuSectionPostNewSheetLibre(Request $request, ManagerRegistry $doctrine ,#[MapEntity(mapping: ['sheet' => 'slug'])] Sheet $sheet ,MenuRepository $menuRepository, TemplateRepository $templateRepository, PostRepository $postRepository): Response
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
            $entityManager = $doctrine->getManager();
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

        $array = [
            'menu' => $menu,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/menu/new_libre.html.twig', $array);
    }


    /**
     * @Route("/page/{sheet}/nouveau-menu/nouveau-contenu-liste", name="menu_section_post_new_sheet_liste", methods={"GET","POST"})
     */
    public function menuSectionPostNewSheetListe(Request $request, ManagerRegistry $doctrine, #[MapEntity(mapping: ['sheet' => 'slug'])] Sheet $sheet , MenuRepository $menuRepository, TemplateRepository $templateRepository, PostRepository $postRepository): Response
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
            $entityManager = $doctrine->getManager();
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

        $array = [
            'menu' => $menu,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/menu/new_liste.html.twig', $array);
    }



    /**
     * @Route("/page/{sheet}/nouveau-menu/nouveau-contenu", name="menu_section_post_new_sheet", methods={"GET","POST"})
     */
    public function menuSectionPostNewSheet(Request $request, ManagerRegistry $doctrine, #[MapEntity(mapping: ['sheet' => 'slug'])] ?Sheet $sheet, MenuRepository $menuRepository, PostRepository $postRepository, ApiClient $apiClient): Response
    {
        $libres = $apiClient->getTemplates('templates', 99);
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
        $post->setSection($section);
        $menu->addSection($section);
        $options['sheet'] = true;
        $options['full_template'] = false;
        if(!is_null($sheet)) {
            $menu->setSheet($sheet);
            $options['sheet'] = false;
        }
        $form = $this->createForm(MenuType::class, $menu,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $position_menu = $menuRepository->getMaxPosition($sheet);
            $menu->setPosition($position_menu);
            $entityManager->persist($section);
            $entityManager->persist($menu);
            $entityManager->persist($post);
            $entityManager->flush();
               if ($form->get('saveAndAddPost')->isClicked()) {
//                $section= $menu->getSections()[0];
                return $this->redirectToRoute('post_new_section', ['section'=> $section->getId()]);
            }
            if ($form->get('saveAndAddSectionPost')->isClicked()) {
//                $section= $menu->getSections()[0];
                return $this->redirectToRoute('section_post_new_menu', ['menu'=> $menu->getSlug(), 'section'=> $section->getId()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('menu_index');
            }
            return $this->redirectToRoute('menu_index');
        }
        $array = [
            'menu' => $menu,
            'form' => $form->createView(),
            'libres' => $libres,
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/menu/new.html.twig', $array);
    }

    /**
     * @Route("/menu/{id}", name="menu_show", methods={"GET"})
     */
    public function show(Menu $menu): Response
    {
        $array = [
            'menu' => $menu,
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);
        return $this->render('admin/menu/show.html.twig', $array);
    }

    /**
     * @Route("/page/{sheet}/menu/{referenceName}/locale/{locale}", name="menu_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ManagerRegistry $doctrine, CacheInterface $backCache,MenuRepository $menuRepository, $sheet, $referenceName, $locale): Response
    {
//        Le menu'est pas unique pour un slug donné, aussi il faut le récupérer avec le slug menu et le sheet
//        $menu = $menuRepository->findOneBy(['slug'=> $menu->getSlug(), 'sheet'=>$sheet]);
        $menu = $menuRepository->findOneBy(['locale'=> $locale, 'referenceName'=>$referenceName]);
        $options['label_name']= 'global.menu';
        $form = $this->createForm(BaseMenuType::class, $menu, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($menu);
            $em->flush();

            $sheet = $menu->getSheet()->getSlug();
            $slug = $menu->getSlug();
            $backCache->delete('front_page_cache_key'.$sheet.$slug);
            if ($form->get('saveAndAdd')->isClicked()) {
                return $this->redirectToRoute('menu_section_post_new_sheet', ['sheet'=> $menu->getSheet()->getSlug()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('menu_index', ['sheet' => $menu->getSheet()->getId()]);
            }
            return $this->redirectToRoute('menu_index');
        }

        $array = [
            'menu' => $menu,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/menu/edit.html.twig', $array);
    }

    /**
     * @Route("/menu/{id}/modele/{section}", name="menu_section_edit", methods={"GET","POST"})
     */
    public function editSection(Request $request, ManagerRegistry $doctrine, Menu $menu, #[MapEntity()] ?Section $section): Response
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
            $em = $doctrine->getManager();
            $em->persist($section);
            $em->flush();

            return $this->redirectToRoute('menu_index');
        }

        $array = [
            'section' => $section,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/section/edit.html.twig', $array);
    }


    /**
     * @Route("/menu/{id}", name="menu_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ManagerRegistry $doctrine, Menu $menu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menu->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
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
