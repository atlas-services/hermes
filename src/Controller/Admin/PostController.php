<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use App\Form\Admin\PostCopyType;
use App\Form\Admin\PostType;
use App\Repository\PostRepository;
use App\Service\Copy;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/{_locale}/admin')]
class PostController extends AbstractAdminController
{
    #[Route(path: '/contenu/', name: 'post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository,ManagerRegistry $doctrine,): Response
    {
        $posts = $postRepository->getEditablePosts();

        $array = [
            'posts' => $posts,
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/post/index.html.twig', $array);
    }

    #[Route(path: '/nouveau-contenu/section/{section}/liste', name: 'post_new_section_liste', methods: ['GET', 'POST'])]
    public function postNewSectionListe(Request $request, ManagerRegistry $doctrine, #[MapEntity()] ?Section $section, PostRepository $postRepository): Response
    {
        $numpost = count($section->getPosts()) + 1;
        $post = new Post();
        $post->setName($section->getMenu()->getName().' '.$numpost);
        $post->setSection($section);
        $options['section'] = false;
        $options['active'] = false;
        $options['position'] = false;
        $options['url'] = false;
        $options['content'] = false;
        $options['startPublishedAt'] = false;
        $options['endPublishedAt'] = false;
        $options['saveAndAddSectionPost'] = true;
        $form = $this->createForm(PostType::class, $post, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $position = $postRepository->getMaxPosition($section);
            $post->setPosition($position);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            if ($form->get('saveAndAddPost')->isClicked()) {
                $section = $post->getSection();
                return $this->redirectToRoute('post_new_section_liste', ['section'=> $section->getId()]);
            }
            if ($form->get('saveAndAddSectionPost')->isClicked()) {
                $section = $post->getSection();
                $menu = $section->getMenu();
                return $this->redirectToRoute('section_post_new_menu', ['menu'=> $menu->getSlug(), 'section'=> $section->getId()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('menu_index');
            }
            return $this->redirectToRoute('menu_index');
        }

        $array = [
            'form' => $form->createView(),
            'menu' => $section->getMenu() ?? '',
            'post' => $post ?? ''
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/post/new.html.twig', $array);
    }


    #[Route(path: '/nouveau-contenu/section/{section}', name: 'post_new_section', methods: ['GET', 'POST'])]
    public function postNewSection(Request $request, ManagerRegistry $doctrine, #[MapEntity()] ?Section $section, PostRepository $postRepository): Response
    {
        $numpost = count($section->getPosts()) + 1;
        $post = new Post();
        $post->setName($section->getMenu()->getName().' '.$numpost);
        $post->setSection($section);
        $options['section'] = false;
        $options['position'] = false;
        $options['saveAndAddSectionPost'] = true;
        $form = $this->createForm(PostType::class, $post, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $position = $postRepository->getMaxPosition($section);
            $post->setPosition($position);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            if ($form->get('saveAndAddPost')->isClicked()) {
                $section = $post->getSection();
                return $this->redirectToRoute('post_new_section', ['section'=> $section->getId()]);
            }
            if ($form->get('saveAndAddSectionPost')->isClicked()) {
                $section = $post->getSection();
                $menu = $section->getMenu();
                return $this->redirectToRoute('section_post_new_menu', ['menu'=> $menu->getSlug(), 'section'=> $section->getId()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('menu_index');
            }
            return $this->redirectToRoute('menu_index');
        }

        $array = [
            'form' => $form->createView(),
            'menu' => $section->getMenu() ?? '',
            'post' => $post ?? ''
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/post/new.html.twig', $array);
    }

    #[Route(path: '/nouveau-contenu', name: 'post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $options= ['section'=> true];
        $post = new Post();
        $form = $this->createForm(PostType::class, $post,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            try{
                return $this->redirectToRoute('section_index', ['menu' => $post->getSection()->getMenu()->getId()]);
            }catch(\Exception $e){
                return $this->redirectToRoute('post_index');
            }
        }

        $array = [
            'post' => $post,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/post/new.html.twig', $array);
    }

    #[Route(path: '/contenu/{id}', name: 'post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {

        $array = [
            'post' => $post,
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/post/show.html.twig', $array);
    }

    #[Route(path: '/menu/{menu}/contenu/{id}/{post}', name: 'post_edit', methods: ['GET', 'POST'], requirements: ['post' => '.+'])]
    public function edit(Request $request, ManagerRegistry $doctrine,$id, #[MapEntity(mapping: ['post' => 'name'])] Post $post, #[MapEntity(mapping: ['menu' => 'slug'])] Menu $menu, PostRepository $postRepository): Response
    {
        $referer = (string) $request->headers->get('referer');
//        Le post'est pas unique pour un name donné, aussi il faut le récupérer avec l'id
        $post = $postRepository->findOneById($id);
        $options = ['section'=> false];
        $options = ['image_file'=> true];
        $options['url'] = true;
        $options['saveAndAddSectionPost'] = true;
        $form = $this->createForm(PostType::class, $post,$options );
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();

            if ($form->get('saveAndAddPost')->isClicked()) {
                $section = $post->getSection();
                return $this->redirectToRoute('post_new_section', ['section'=> $section->getId()]);
            }
            if ($form->get('saveAndAddSectionPost')->isClicked()) {
                $section = $post->getSection();
                $menu = $section->getMenu();
                return $this->redirectToRoute('section_post_new_menu', ['menu'=> $menu->getSlug(), 'section'=> $section->getId()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('section_index', ['menu' => $menu->getId()]);
            }

            try{
                return $this->redirectToRoute('section_index', ['menu' => $post->getSection()->getMenu()->getId()]);
            }catch(\Exception $e){
                return $this->redirectToRoute('post_index');
            }
        }

        $array = [
            'post' => $post,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/post/edit.html.twig', $array);
    }

    #[Route(path: '/contenu/{id}', name: 'post_delete', methods: ['POST', 'DELETE'])]
    public function delete(Request $request, ManagerRegistry $doctrine, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        try{
            return $this->redirectToRoute('section_index', ['menu' => $post->getSection()->getMenu()->getId()]);
        }catch(\Exception $e){
            return $this->redirectToRoute('post_index');
        }

    }

    #[Route(path: '/ajax/switch/post', name: 'switch_post_active_ajax')]
    public function ajaxActive(Request $request, PostRepository $postRepository)
    {
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('id');
            $data = $postRepository->switchActive($id);
            return new JsonResponse(array('data' =>$data->isActive()));
        }

        return new Response('This is not ajax!', 400);
    }



    #[Route(path: '/contenu/copy/{post}', name: 'post_copy', methods: ['GET', 'POST'])]
    public function copy(Request $request, #[MapEntity(mapping: ['post' => 'id'])] Post $post, Copy $copy): Response
    {
        $form = $this->createForm(PostCopyType::class, $post);
        $fromPost = clone $post;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('move')->isClicked()) {
                $copy->copyPost($post, $fromPost, false);
                try{
                    return $this->redirectToRoute('section_index', ['menu' => $post->getSection()->getMenu()->getId()]);
                }catch(\Exception $e){
                    return $this->redirectToRoute('post_index');
                }
                return $this->redirectToRoute('post_index');
            }
            if ($form->get('copy')->isClicked()) {
                $copy->copyPost($post, $fromPost, true);
                try{
                    return $this->redirectToRoute('section_index', ['menu' => $post->getSection()->getMenu()->getId()]);
                }catch(\Exception $e){
                    return $this->redirectToRoute('post_index');
                }
                return $this->redirectToRoute('post_index');
            }

            try{
                return $this->redirectToRoute('section_index', ['menu' => $post->getSection()->getMenu()->getId()]);
            }catch(\Exception $e){
                return $this->redirectToRoute('post_index');
            }

            return $this->redirectToRoute('post_index');
        }

        $array = [
            'post' => $post,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/post/copy.html.twig', $array);
    }


}
