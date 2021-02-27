<?php

namespace App\Controller\Admin;

use App\Entity\Hermes\User;
use App\Form\Admin\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if(!$this->isGranted('ROLE_SUPER_ADMIN')){
            $this->redirectToRoute('user_index');
        }

        $user = new User();
        $options['roles'] = $this->getRoles($user);
        $form = $this->createForm(UserType::class, $user, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        if(!$this->isGranted('ROLE_SUPER_ADMIN')){
            $this->redirectToRoute('user_index');
        }

        if($user->idSuperAdmin() ){
            $options['disable_roles'] = true;
        }
        $options['roles'] = $this->getRoles($user);
        $form = $this->createForm(UserType::class, $user, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    private function getRoles($user){
        if($this->isGranted('ROLE_SUPER_ADMIN')){
            foreach ($this->getUser()->getRoles() as $role){
                $roles_super_admin[$role] = $role;
            }
            foreach ($user->getRoles() as $role){
                $roles_user[$role] = $role;
            }
            $roles = array_merge($roles_super_admin, $roles_user);
        }else{
            foreach ($user->getRoles() as $role){
                $roles[$role] = $role;
            }
        }
        return $roles;
    }
}
