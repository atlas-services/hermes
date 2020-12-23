<?php

namespace App\Controller\Ecommerce\Admin;

use App\Entity\Order;
use App\Entity\Menu;
use App\Entity\Sheet;
use App\Form\Admin\Ecommerce\OrderType;
use App\Repository\OrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/customer/order")
 */
class OrderController extends AbstractController
{

    /**
     * @Route("/", name="order_index", methods={"GET"})
     */
    public function index(OrderRepository $orderRepository): Response
    {

        $orders = $orderRepository->findByUser($this->getUser());

        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/{id}", name="order_show", methods={"GET"})
     */
    public function show(Order $order): Response
    {
        return $this->render('admin/order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="order_edit", methods={"GET","POST"})
     * @ParamConverter("order", class="App\Entity\Order")
     */
    public function edit(Request $request, Order $order): Response
    {

        if($this->isGranted('ROLE_CUSTOMER') and !$this->isGranted('ROLE_ADMIN')){
            $order->setUser($this->getUser());
        }
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_index');
        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="order_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Order $order): Response
    {
        if(!$this->isGranted('ROLE_CUSTOMER')){
            $this->redirectToRoute('order_index');
        }

        if ($this->isCsrfTokenValid('delete' . $order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_index');
    }

    public function updateForm($menus,$listform,$listform_init)
    {

        $forms_init = explode(',', $listform_init);
        $forms = explode(',', $listform);
        $forms_add = array_diff($forms,$forms_init);// add
        $forms_delete =  array_diff($forms_init,$forms);//delete

        foreach ($forms_delete as $form){
            $sheet_form = $this->getDoctrine()->getManager()->getRepository(Sheet::class)->findOneBy(['active' => true, 'name' => $form]);
            if(!is_null($sheet_form)) {
                $this->getDoctrine()->getManager()->remove($sheet_form);
            }
        }

        foreach ($forms_add as $form){
            if (!array_key_exists(strtoupper($form), $menus)) {
                $sheet_form = $this->getDoctrine()->getManager()->getRepository(Sheet::class)->findOneBy(['active' => true, 'name' => $form]);
                // Création sheet si le formulaire n'existe pas
                if(is_null($sheet_form)){
                    $newSheet = new Sheet();
                    $newSheet->setCode($form);
                    $newSheet->setName($form);
                    $newSheet->setSlug($form);
                    $this->getDoctrine()->getManager()->persist($newSheet);
                }
            }
        }
        $this->getDoctrine()->getManager()->flush();
    }
}
