<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\BlockPost;
use App\Entity\Hermes\Block;
use App\Form\Admin\BlockPostType;
use App\Form\Admin\BlockType;
use App\Repository\BlockRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Runner\Exception;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin")
 */
class BlockController extends AbstractController
{
    /**
     * @Route("/block/", name="block_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $blocks = $doctrine
            ->getRepository(Block::class)
            ->findAll()
        ;

        return $this->render('admin/block/index.html.twig', [
            'blocks' => $blocks,
        ]);
    }


    /**
     * @Route("/menu/{menu}/nouveau-block/nouveau-contenu", name="block_post_new_menu", methods={"GET","POST"})
     */
    public function BlockPostNewMenu(Request $request, ManagerRegistry $doctrine, ?Menu $menu): Response
    {
        $block = new Block() ;
        $post = new BlockPost();
        $block->addBlockPost($post);

        $block->setName($menu->getName().rand(0,9999));
        $options['menu'] = false;
        $options['position'] = false;
        $options['content'] = true;
        $form = $this->createForm(BlockType::class, $block, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $doctrine->getManager();
            $entityManager->persist($block);
            $entityManager->flush();
            if ($form->get('saveAndAddPost')->isClicked()) {
                return $this->redirectToRoute('post_new_block', ['block'=> $block->getId()]);
            }
            if ($form->get('saveAndAddPost')->isClicked()) {
                return $this->redirectToRoute('block_post_new_menu', ['menu'=> $menu->getSlug()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('block_index');
            }
            return $this->redirectToRoute('menu_index');
        }

        return $this->render('admin/block/new.html.twig', [
            'form' => $form->createView(),
            'menu' => $block->getMenu() ?? ''
        ]);
    }

    /**
     * @Route("/nouveau-block", name="block_new", methods={"GET","POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
//        $post = new BlockPost();
        $block = new Block();
//        $block->addBlockPost($post);
        $form = $this->createForm(BlockType::class, $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($block);
            $entityManager->flush();

            return $this->redirectToRoute('block_index');
        }

        return $this->render('admin/block/new.html.twig', [
            'block' => $block,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/block/{id}", name="block_show", methods={"GET"})
     */
    public function show(Block $block): Response
    {
        return $this->render('admin/block/show.html.twig', [
            'block' => $block,
        ]);
    }

    /**
     * @Route("/block/edit/{block}", name="block_edit", methods={"GET","POST"})
     * 
     */
    public function edit(Request $request, ManagerRegistry $doctrine, #[MapEntity(mapping: ['block' => 'id'])] Block $block): Response
    {

        $form = $this->createForm(BlockType::class, $block);
//        dump($block);
//        dump($block->getBlockPosts());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            dd($block->getBlockPosts());
            $em = $doctrine->getManager();
            $em->persist($block);
            $em->flush();

            if ($form->get('saveAndAddPost')->isClicked()) {
                return $this->redirectToRoute('post_new_block', ['block'=> $block->getId()]);
            }
            if ($form->get('saveAndAddPost')->isClicked()) {
//                $menu = $block->getMenu();
//                return $this->redirectToRoute('block_post_new_menu', ['menu'=> $menu->getSlug(), 'block'=> $block->getId()]);
            }
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('block_index');
            }
            return $this->redirectToRoute('block_index');
        }

        return $this->render('admin/block/edit.html.twig', [
            'block' => $block,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/block/{id}", name="block_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ManagerRegistry $doctrine, Block $block): Response
    {
        if ($this->isCsrfTokenValid('delete'.$block->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($block);
            $entityManager->flush();
        }

        return $this->redirectToRoute('block_index');
    }

    /**
     * @Route("/ajax/switch/block", name="switch_block_active_ajax")
     */
    public function ajaxActive(Request $request, BlockRepository $blockRepository)
    {
        if ($request->isXMLHttpRequest()) {
            $id = $request->request->get('id');
            $data = $blockRepository->switchActive($id);
            return new JsonResponse(array('data' =>$data->isActive()));
        }

        return new Response('This is not ajax!', 400);
    }


}
