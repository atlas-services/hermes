<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Admin;

use App\Controller\Front\FrontController;
use App\Menu\Page;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class PrevisualisationController extends FrontController
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
        $array = $page->getActiveMenu($sheet,$slug);

        return $this->render('front/index.html.twig', $array);
    }

}
