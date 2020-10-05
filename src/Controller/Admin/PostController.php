<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\Post;
use App\Entity\Section;
use App\Form\Admin\PostType;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/{_locale}/admin")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/contenu/", name="post_index", methods={"GET"})
     */
    public function index(CacheInterface $backCache): Response
    {
        $response = $backCache->get('back_post_index_cache_key', function (ItemInterface $item) {
            $item->expiresAfter(2);

            $posts = $this->getDoctrine()
                ->getRepository(Post::class)
                ->findAll();

            $computedResponse = $this->render('admin/post/index.html.twig', [
                'posts' => $posts,
            ]);

            return $computedResponse;
        });

        return $response;

    }

    /**
     * @Route("/nouveau-contenu/modele/{section}", name="post_new_section", methods={"GET","POST"})
     * @ParamConverter("section", class="App\Entity\Section")
     */
    public function postNewSection(Request $request, ?Section $section, PostRepository $postRepository): Response
    {
        $post = new Post();
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
     * @Route("/menu/{menu}/contenu/{id}/{post}", name="post_edit", methods={"GET","POST"})
     * @ParamConverter("post",class="App\Entity\Post", options={"mapping": {"post": "name"}})
     * @ParamConverter("menu",class="App\Entity\Menu", options={"mapping": {"menu": "slug"}})
     */
    public function edit(Request $request,$id, Post $post, Menu $menu, PostRepository $postRepository, CacheInterface $backCache): Response
    {
//        Le post'est pas unique pour un name donné, aussi il faut le récupérer avec l'id
        $post = $postRepository->findOneById($id);
        $options = ['section'=> false];
        $options = ['image_file'=> true];
        $options['saveAndAddSectionPost'] = true;
        $form = $this->createForm(PostType::class, $post,$options );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $sheet = $post->getSection()->getMenu()->getSheet()->getSlug();
            $slug = $post->getSection()->getMenu()->getSlug();
            $backCache->delete('front_page_cache_key'.$sheet.$slug);

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
