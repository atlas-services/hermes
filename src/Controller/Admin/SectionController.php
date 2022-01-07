<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use App\Entity\Hermes\Menu;
//use App\Form\SectionType;
use App\Form\Admin\PostType;
use App\Form\Admin\SectionCopyType;
use App\Form\Admin\SectionTemplateType;
use App\Form\Admin\SectionType;
use App\Repository\SectionRepository;
use App\Service\Copy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin")
 */
class SectionController extends AbstractAdminController
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

        $array = [
            'sections' => $sections,
            'menus' => $menus,
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/section/index.html.twig', $array );
    }


    /**
     * @Route("/menu/{menu}/nouveau-modele/nouveau-contenu", name="section_post_new_menu", methods={"GET","POST"})
     * @ParamConverter("menu",class="App\Entity\Hermes\Menu", options={"mapping": {"menu": "slug"}})
     */
    public function SectionPostNewMenu(Request $request, ?Menu $menu): Response
    {
        $section = new Section() ;
        $post = new Post();
        $section->addPost($post);
        $section->setMenu($menu);
        $section->setName($menu->getName().rand(0,9999));
        $options['menu'] = false;
        $options['position'] = true;
        $options['content'] = true;
        $options['url'] = true;
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

        $array = [
            'form' => $form->createView(),
            'menu' => $section->getMenu() ?? ''
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/section/new.html.twig', $array);
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

        $array = [
            'section' => $section,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/section/new.html.twig', $array);
    }

    /**
     * @Route("/modele/{id}", name="section_show", methods={"GET"})
     */
    public function show(Section $section): Response
    {
        $array = [
            'section' => $section,
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/section/show.html.twig', $array);
    }

    /**
     * @Route("/modele/edit/{section}/{config}", name="section_edit", methods={"GET","POST"})
     * @ParamConverter("section",class="App\Entity\Hermes\Section", options={"mapping": {"section": "id"}})
     */
    public function edit(Request $request, Section $section, $config = 1): Response
    {

        $options['posts'] = [
            'entry_type' => PostType::class,
            'constraints' => new \Symfony\Component\Validator\Constraints\Valid(),
            'prototype'=> true,
            'prototype_name' => 'post',
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'label'=> false,
            'entry_options' => ['label' => false, 'active'=> false, 'position'=>true, 'name'=>true, 'content'=>false, 'save_visibility' => false, 'save' => false, 'saveAndAdd' => false, 'saveAndAddPost' => false, 'saveAndAddSectionPost' => false,],
        ];

        // On peut gérer les images remote ici => true
        $options['config'] = boolval($config);

        $form = $this->createForm(SectionTemplateType::class, $section, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($section);
            $em->flush();

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

        $array = [
            'section' => $section,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/section/edit.html.twig', $array);
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


    /**
     * @Route("/modele/copy/{section}", name="section_copy", methods={"GET","POST"})
     * @ParamConverter("section",class="App\Entity\Hermes\Section", options={"mapping": {"section": "id"}})
     */
    public function copy(Request $request, Section $section, Copy $copy): Response
    {
        $form = $this->createForm(SectionCopyType::class, $section);
        $fromSection = clone $section;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('move')->isClicked()) {
                $copy->copySection($section, $fromSection, false);
                return $this->redirectToRoute('section_index');
            }
            if ($form->get('copy')->isClicked()) {
                $copy->copySection($section, $fromSection, true);
                return $this->redirectToRoute('section_index');
            }

            return $this->redirectToRoute('section_index');
        }

        $array = [
            'section' => $section,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($array);

        return $this->render('admin/section/copy.html.twig', $array);
    }


}
