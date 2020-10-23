<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\Section;
use App\Entity\Menu;
//use App\Form\SectionType;
use App\Form\Admin\PostType;
use App\Form\Admin\SectionTemplateType;
use App\Form\Admin\SectionType;
use App\Repository\SectionRepository;
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
class SectionController extends AbstractController
{
    /**
     * @Route("/modele/", name="section_index", methods={"GET"})
     */
    public function index(): Response
    {
        $sections = $this->getDoctrine()
            ->getRepository(Section::class)
            ->findAll()
        ;
        $menus = $this->getDoctrine()
            ->getRepository(Menu::class)
            ->findAll()
        ;

        return $this->render('admin/section/index.html.twig', [
            'sections' => $sections,
            'menus' => $menus,
        ]);
    }


    /**
     * @Route("/menu/{menu}/nouveau-modele/nouveau-contenu", name="section_post_new_menu", methods={"GET","POST"})
     * @ParamConverter("menu",class="App\Entity\Menu", options={"mapping": {"menu": "slug"}})
     */
    public function SectionPostNewMenu(Request $request, ?Menu $menu): Response
    {
        $section = new Section() ;
        $post = new Post();
        $section->addPost($post);
        $section->setMenu($menu);
        $section->setName($menu->getName().rand(0,9999));
        $options['menu'] = false;
        $options['position'] = false;
        $options['content'] = true;
        $form = $this->createForm(SectionTemplateType::class, $section, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();
            if ($form->get('saveAndAddPost')->isClicked()) {
                return $this->redirectToRoute('post_new_section', ['section'=> $section->getId()]);
            }
            if ($form->get('saveAndAddSectionPost')->isClicked()) {
                return $this->redirectToRoute('section_post_new_menu', ['menu'=> $menu->getSlug()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('section_index');
            }
            return $this->redirectToRoute('menu_index');
        }

        return $this->render('admin/section/new.html.twig', [
            'form' => $form->createView(),
            'menu' => $section->getMenu() ?? ''
        ]);
    }

    /**
     * @Route("/nouveau-modele", name="section_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $section = new Section();
        $section->addPost($post);
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('section_index');
        }

        return $this->render('admin/section/new.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modele/{id}", name="section_show", methods={"GET"})
     */
    public function show(Section $section): Response
    {
        return $this->render('admin/section/show.html.twig', [
            'section' => $section,
        ]);
    }

    /**
     * @Route("/modele/edit/{section}", name="section_edit", methods={"GET","POST"})
     * @ParamConverter("section",class="App\Entity\Section", options={"mapping": {"section": "id"}})
     */
    public function edit(Request $request, CacheInterface $backCache, Section $section): Response
    {
        $options['position'] = true;
        $options['content'] = false;
        $form = $this->createForm(SectionTemplateType::class, $section, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($section);
            $em->flush();

            $sheet = $section->getMenu()->getSheet()->getSlug();
            $slug = $section->getMenu()->getSlug();
            $backCache->delete('front_page_cache_key'.$sheet.$slug);
            if ($form->get('saveAndAddPost')->isClicked()) {
                return $this->redirectToRoute('post_new_section', ['section'=> $section->getId()]);
            }
            if ($form->get('saveAndAddSectionPost')->isClicked()) {
                $menu = $section->getMenu();
                return $this->redirectToRoute('section_post_new_menu', ['menu'=> $menu->getSlug(), 'section'=> $section->getId()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('section_index');
            }
            return $this->redirectToRoute('section_index');
        }

        return $this->render('admin/section/edit.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modele/{id}", name="section_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Section $section): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($section);
            $entityManager->flush();
        }

        return $this->redirectToRoute('section_index');
    }

    /**
     * @Route("/ajax/switch/section", name="switch_section_active_ajax")
     */
    public function ajaxActive(Request $request, SectionRepository $sectionRepository)
    {
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('id');
            $data = $sectionRepository->switchActive($id);
            return new JsonResponse(array('data' =>$data->isActive()));
        }

        return new Response('This is not ajax!', 400);
    }


}
