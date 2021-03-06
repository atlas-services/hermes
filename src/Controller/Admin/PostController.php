<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use App\Form\Admin\PostType;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/contenu/", name="post_index", methods={"GET"})
     */
    public function index(): Response
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        return $this->render('admin/post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/nouveau-contenu/modele/{section}", name="post_new_section", methods={"GET","POST"})
     * @ParamConverter("section", class="App\Entity\Hermes\Section")
     */
    public function postNewSection(Request $request, ?Section $section, PostRepository $postRepository): Response
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
            $entityManager = $this->getDoctrine()->getManager();
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

        return $this->render('admin/post/new.html.twig', [
            'form' => $form->createView(),
            'menu' => $section->getMenu() ?? '',
            'post' => $post ?? ''
        ]);
    }


    /**
     * @Route("/nouveau-contenu", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $options= ['section'=> true];
        $post = new Post();
        $form = $this->createForm(PostType::class, $post,$options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('admin/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contenu/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {

        return $this->render('admin/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/menu/{menu}/contenu/{id}/{post}", name="post_edit", methods={"GET","POST"}, requirements={"post"=".+"})
     * @ParamConverter("post",class="App\Entity\Hermes\Post", options={"mapping": {"post": "name"}})
     * @ParamConverter("menu",class="App\Entity\Hermes\Menu", options={"mapping": {"menu": "slug"}})
     */
    public function edit(Request $request,$id, Post $post, Menu $menu, PostRepository $postRepository): Response
    {
//        Le post'est pas unique pour un name donné, aussi il faut le récupérer avec l'id
        $post = $postRepository->findOneById($id);
        $options = ['section'=> false];
        $options = ['image_file'=> true];
        $options['url'] = true;
        $options['saveAndAddSectionPost'] = true;
        $form = $this->createForm(PostType::class, $post,$options );
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

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

            }
            return $this->redirectToRoute('post_index');
        }

        return $this->render('admin/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contenu/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * @Route("/ajax/switch/post", name="switch_post_active_ajax")
     */
    public function ajaxActive(Request $request, PostRepository $postRepository)
    {
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('id');
            $data = $postRepository->switchActive($id);
            return new JsonResponse(array('data' =>$data->isActive()));
        }

        return new Response('This is not ajax!', 400);
    }

}
