<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Admin;

use App\Controller\Front\FrontController;
use App\Controller\Admin\AbstractAdminController;
use App\Entity\Hermes\Sheet;
use App\Menu\Page;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PrevisualisationController extends AbstractAdminController
{
    /**
     * @Route(
     *     "/{_locale}/admin/previsualisation/{sheet}/{slug}",
     *     name="previsualidation_sheet",
     *     methods={"GET|POST"}
     *     )
     */
    public function previsualisation(Request $request, Page $page, $sheet = 'accueil', $slug = 'accueil')
    {
        $array = [];
        $array = $this->mergeActiveConfig($doctrine, $array);
        if( Sheet::ONE_PAGE_LIBELLE == $array['nav_bar']){
            return $this->redirect('/');
        }
        return $this->render('front/index.html.twig', $array);
    }

};
