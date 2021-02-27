<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\BlockPost;
use App\Entity\Hermes\Block;
use App\Form\Admin\BlockPostType;
use App\Repository\BlockPostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin")
 */
class BlockPostController extends AbstractController
{
    /**
     * @Route("/blockcontenu/", name="blockpost_index", methods={"GET"})
     */
    public function index(): Response
    {
        $posts = $this->getDoctrine()
            ->getRepository(BlockPost::class)
            ->findAll();

        return $this->render('admin/blockpost/index.html.twig', [
            'blockposts' => $posts,
        ]);
    }

     /**
     * @Route("/nouveau-contenu", name="blockpost_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $post = new BlockPost();
        $form = $this->createForm(BlockPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('blockpost_index');
        }

        return $this->render('admin/blockpost/new.html.twig', [
            'blockpost' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contenu/{id}", name="blockpost_show", methods={"GET"})
     */
    public function show(BlockPost $post): Response
    {

        return $this->render('admin/blockpost/show.html.twig', [
            'blockpost' => $post,
        ]);
    }

    /**
     * @Route("/block/block-contenu/{id}", name="blockpost_edit", methods={"GET","POST"}, requirements={"blockpost"=".+"})
     */
    public function edit(Request $request,$id, BlockPostRepository $postRepository): Response
    {
//        Le post'est pas unique pour un name donné, aussi il faut le récupérer avec l'id
        $post = $postRepository->findOneById($id);
        $options = ['block'=> false];
        $options = ['image_file'=> true];
        $options['saveAndAddPost'] = true;
        $form = $this->createForm(BlockPostType::class, $post,$options );
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if ($form->get('saveAndAddPost')->isClicked()) {
                $block = $post->getBlock();
                return $this->redirectToRoute('post_new_block', ['block'=> $block->getId()]);
            }
            if ($form->get('saveAndAddPost')->isClicked()) {
                $block = $post->getBlock();
                $menu = $block->getMenu();
                return $this->redirectToRoute('block_post_new_menu', ['menu'=> $menu->getSlug(), 'block'=> $block->getId()]);
            }
            if ($form->get('save')->isClicked()) {

            }
            return $this->redirectToRoute('blockpost_index');
        }

        return $this->render('admin/blockpost/edit.html.twig', [
            'blockpost' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contenu/{id}", name="blockpost_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BlockPost $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blockpost_index');
    }

    /**
     * @Route("/ajax/switch/blockpost", name="switch_blockpost_active_ajax")
     */
    public function ajaxActive(Request $request, BlockPostRepository $postRepository)
    {
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('id');
            $data = $postRepository->switchActive($id);
            return new JsonResponse(array('data' =>$data->isActive()));
        }

        return new Response('This is not ajax!', 400);
    }

}
