<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\BlockPost;
use App\Form\Admin\BlockPostType;
use App\Repository\BlockPostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

/**
 * @Route("/{_locale}/admin")
 */
class BlockPostController extends AbstractController
{
    /**
     * @Route("/block/contenu/", name="blockpost_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $posts = $doctrine
            ->getRepository(BlockPost::class)
            ->findAll();

        return $this->render('admin/blockpost/index.html.twig', [
            'blockposts' => $posts,
        ]);
    }

     /**
     * @Route("/block/nouveau-contenu", name="blockpost_new", methods={"GET","POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {

        $post = new BlockPost();
        $form = $this->createForm(BlockPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
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
     * @Route("/block/contenu/{id}", name="blockpost_show", methods={"GET"})
     */
    public function show(BlockPost $post): Response
    {

        return $this->render('admin/blockpost/show.html.twig', [
            'blockpost' => $post,
        ]);
    }

    /**
     * @Route("/block/contenu/{id}", name="blockpost_edit", methods={"GET","POST"}, requirements={"blockpost"=".+"})
     */
    public function edit(Request $request,$id, ManagerRegistry $doctrine, BlockPostRepository $postRepository): Response
    {
//        Le post'est pas unique pour un name donné, aussi il faut le récupérer avec l'id
        $post = $postRepository->findOneById($id);
        $options = ['block'=> false];
        $options = ['image_file'=> true];
        $options['saveAndAddPost'] = true;
        $form = $this->createForm(BlockPostType::class, $post,$options );
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();

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
     * @Route("/block/contenu/{id}", name="blockpost_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ManagerRegistry $doctrine, #[MapEntity(mapping: ['blockpost' => 'post'])] BlockPost $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
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
