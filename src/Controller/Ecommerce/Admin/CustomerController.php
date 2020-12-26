<?php

namespace App\Controller\Ecommerce\Admin;

use App\Entity\Sheet;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/customer")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("/", name="customer_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $referer = $request->headers->get('referer'); // get the referer
        if(false == strpos($referer, 'login')){
            return $this->redirectToRoute('order_delivery');
        }
        $array = [];

        return $this->render('admin/customer/index.html.twig', $array);
    }

}
