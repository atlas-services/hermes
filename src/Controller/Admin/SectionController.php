<?php

namespace App\Controller\Admin;

use App\Api\ApiClient;
use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Template;
use App\Entity\Interfaces\ContactInterface;
//use App\Form\SectionType;
use App\Form\Admin\PostType;
use App\Form\Admin\SectionCopyType;
use App\Form\Admin\SectionTemplateType;
use App\Form\Admin\SectionType;
use App\Mailer\Mailer;
use App\Repository\MenuRepository;
use App\Repository\SectionRepository;
use App\Repository\UserRepository;
use App\Service\Copy;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/{_locale}/admin')]
class SectionController extends AbstractAdminController
{
    #[Route(path: '/section/{menu}', name: 'section_index', defaults: ['menu' => 'All'], methods: ['GET'])]
    public function index(ManagerRegistry $doctrine, SectionRepository $sectionRepository, MenuRepository $menuRepository, $menu): Response
    {
        // $sections = $doctrine
        // ->getRepository(Section::class)
        // ->findAll()
        // ;
        $sections = $sectionRepository->getArrayResults();
        if('All' === $menu){
        // $menus = $doctrine
        //     ->getRepository(Menu::class)
        //     ->findAll()
        // ;
        $menus = $menuRepository->getArrayResults();
        }else{
            $sections = $doctrine
            ->getRepository(Section::class)
            ->findBy(['menu' => $menu])
            ;
            $menus = $doctrine
                ->getRepository(Menu::class)
                ->findBy(['id'=> $menu])
            ;
        }

        $array = [
            'sections' => $sections,
            'menus' => $menus,
            'id' => $menu,
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/section/index.html.twig', $array );
    }

    #[Route(path: '/newsletters', name: 'section_newsletter', methods: ['GET'])]
    public function newsletters(ManagerRegistry $doctrine): Response
    {

        $newsletter_template = $doctrine
        ->getRepository(Template::class)
        ->findOneBy(['code' => 'newsletter_template'])
        ;       
        
        $sections = $doctrine
        ->getRepository(Section::class)
        ->findBy(['template' => $newsletter_template])
        ;

        $array = [
            'sections' => $sections,
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/section/newsletter.html.twig', $array );
    }


    #[Route(path: '/menu/{menu}/nouvelle-section/nouveau-contenu', name: 'section_post_new_menu', methods: ['GET', 'POST'])]
    public function SectionPostNewMenu(Request $request, ManagerRegistry $doctrine, Copy $copy,ApiClient $apiClient , #[MapEntity(mapping: ['menu' => 'slug'])] ?Menu $menu): Response
    {
        $sections = $menu->getSections();
        foreach($sections as $section){
            if(Template::TEMPLATE_FORM == $section->getTemplate()->getType()){
                $options['active_form'] = false;
            }
        }
        $libres = $apiClient->getTemplates('templates', 99);
        $section = new Section() ;
        $post = new Post();
        $section->addPost($post);
        $section->setMenu($menu);
        $section->setName($menu->getName().rand(0,9999));
        $options['menu'] = false;
        $options['position'] = true;
        $options['content'] = true;
        $options['url'] = true;
        $options['full_template'] = false;
        $form = $this->createForm(SectionTemplateType::class, $section, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($section);
            $entityManager->flush();
            if ($form->get('saveAndAddPost')->isClicked()) {
                return $this->redirectToRoute('post_new_section', ['section'=> $section->getId()]);
            }
            if ($form->get('saveAndAddSectionPost')->isClicked()) {
                return $this->redirectToRoute('section_post_new_menu', ['menu'=> $menu->getSlug()]);
            }
            // if ($form->get('save')->isClicked()) {
                if(isset($form['uploaded']) && 'liste' === $section->getTemplate()->getType()){
                    foreach($section->getPosts() as $post){
                        $section->removePost($post);
                        $entityManager->persist($section);
                        $entityManager->flush();
                    }
                    $selected_dir = ($form['uploaded']->getData());
                    if(!is_null($selected_dir)){
                        $copy->handleUploadedDir($section, $selected_dir);
                    }
                // }

                return $this->redirectToRoute('section_index');
            }
            return $this->redirectToRoute('menu_index');
        }

        $array = [
            'form' => $form->createView(),
            'menu' => $section->getMenu() ?? '',
            'libres' => $libres
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/section/new.html.twig', $array);
    }

    #[Route(path: '/nouvelle-section', name: 'section_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $post = new Post();
        $section = new Section();
        $section->addPost($post);
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('section_index');
        }

        $array = [
            'section' => $section,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/section/new.html.twig', $array);
    }

    #[Route(path: '/section/{id}', name: 'section_show', methods: ['GET'])]
    public function show(Section $section): Response
    {
        $array = [
            'section' => $section,
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/section/show.html.twig', $array);
    }

    #[Route(path: '/section/edit/{section}/{config}', name: 'section_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ManagerRegistry $doctrine, #[MapEntity(mapping: ['section' => 'id'])] Section $section, $config = 1): Response
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

        $options['type_template'] = $section->getTemplate()->getType();

        // On peut gérer les images remote ici => true
        $options['config'] = boolval($config);

        $form = $this->createForm(SectionTemplateType::class, $section, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();
            if(Template::TEMPLATE_TYPE_LISTE ==  $section->getTemplate()->getType()){
                foreach($section->getPosts() as $post){
                    if(is_null($post->getFilename())){
                        $em->remove($post);
                    }
                }
                $em->flush();
            }

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
                return $this->redirectToRoute('section_index', ['menu' => $section->getMenu()->getId()]);
            }
            return $this->redirectToRoute('section_index');
        }

        $array = [
            'section' => $section,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/section/edit.html.twig', $array);
    }

    #[Route(path: '/section/{id}', name: 'section_delete', methods: ['POST', 'DELETE'])]
    public function delete(Request $request, ManagerRegistry $doctrine, Section $section): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($section);
            $entityManager->flush();
        }

        return $this->redirectToRoute('section_index');
    }

    #[Route(path: '/ajax/switch/section', name: 'switch_section_active_ajax')]
    public function ajaxActive(Request $request, SectionRepository $sectionRepository)
    {
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('id');
            $data = $sectionRepository->switchActive($id);
            return new JsonResponse(array('data' =>$data->isActive()));
        }

        return new Response('This is not ajax!', 400);
    }


    #[Route(path: '/section/copy/{section}', name: 'section_copy', methods: ['GET', 'POST'])]
    public function copy(Request $request, ManagerRegistry $doctrine, #[MapEntity(mapping: ['section' => 'id'])] Section $section, Copy $copy): Response
    {
        $initMenu = $doctrine->getRepository(Section::class)->find($section->getId())->getMenu();
        $form = $this->createForm(SectionCopyType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $toSection = clone $section;
            $section->setMenu($initMenu);
            if ($form->get('move')->isClicked()) {
                $bcopy = false;
            }
            if ($form->get('copy')->isClicked()) {
                // $section->setMenu($initMenu);
                $bcopy = true;
            }
            $copy->copySection($section, $toSection, $bcopy);
            return $this->redirectToRoute('section_index');
        }

        $array = [
            'section' => $section,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/section/copy.html.twig', $array);
    }



    #[Route(path: '/section/sendNewsletter/{section}/{test}', name: 'section_send_newsletter', methods: ['GET', 'POST'])]
    public function sendNewsletter(Request $request, #[MapEntity(mapping: ['section' => 'id'])] Section $section, Mailer $mailer, UserRepository $userRepository, $test=null): Response
    { 
        $newsletter_emails = [];
        $referer = (string) $request->headers->get('referer'); // get the referer, it can be empty!
        $subject = "Newsletter";
        if(isset( $section->getPosts()[0])){
            $subject = $section->getPosts()[0]->getName() ;
        }

        if(is_null($test)){
            $newsletter_emails = $userRepository->findNewsletterEmails("ROLE_NEWSLETTER");
        }else{
            $subject .= "(Test)";
            $newsletter_emails = $userRepository->findNewsletterEmails("ROLE_TEST_NEWSLETTER");
        }

        $template = 'newsletter/newsletter.html.twig';

        $array = ['section' => $section];

        //return $this->render('newsletter/newsletter.html.twig', $array);

        if ( 'newsletter_template' == $section->getTemplate()->getCode()){
            $message = $mailer->sendNewsletter($subject, $newsletter_emails, $template, $array);
            $type = $message['type'];
            $libelle = $message['message'];
            if(is_null($test)){
                $this->addFlash($type, $libelle);
            }else{
                $this->addFlash($type, "$libelle (TEST)");
            }
        }else{
            $this->addFlash('danger', 'Pas de Newletter!');
        }

        return $this->redirect($referer);
    }


}
