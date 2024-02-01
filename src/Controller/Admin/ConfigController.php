<?php

namespace App\Controller\Admin;

use App\Entity\Config\Config;
use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Sheet;
use App\Form\Admin\ConfigType;
use App\Repository\ConfigRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/config")
 */
class ConfigController extends AbstractAdminController
{

    /**
     * @Route("/navbar-type/{type}", name="config_navbar_type", methods={"GET"})
     */
    public function switchType(Request  $request, ManagerRegistry $doctrine, ConfigRepository $configRepository, $type): Response
    {
        $nav_bar = $configRepository->findOneBy(['code'=> 'nav_bar' ]);

        $nav_bar->setValue($type);

        $entityManager = $doctrine->getManager('config');
        $entityManager->persist($nav_bar);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/new", name="config_new", methods={"GET","POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $options['code_disabled'] = true;
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $options['code_disabled'] = false;
        }
        $config = new Config();
        $form = $this->createForm(ConfigType::class, $config, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager('config');
            $entityManager->persist($config);
            $entityManager->flush();

            return $this->redirectToRoute('admin_index');
        }

        $array = [
            'config' => $config,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);

        return $this->render('admin/config/new.html.twig', $array);
    }

    /**
     * @Route("/{type}", name="config_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine, $type): Response
    {
        $configuration = $doctrine
            ->getRepository(Config::class, 'config')->createQueryBuilder('c')
            ->where('c.type = :type')
            ->setParameter('type', $type)
            ->orderBy('c.code', 'ASC')            ->getQuery()
            ->getResult();
        $configRepository = $doctrine->getRepository(Config::class, 'config');

        if('undefined'== $type){
            $type=null;
        }

        $config = $configRepository->getConfigByTypeOrderByCode($type);
        $array = [
            'configs' => $config,
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);
        return $this->render('admin/config/index.html.twig', $array);
    }

    /**
     * @Route("/{id}", name="config_show", methods={"GET"})
     */
    public function show(Config $config): Response
    {
        $array = [
            'config' => $config,
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);
        return $this->render('admin/config/show.html.twig', $array);
    }

    /**
     * @Route("/{id}/edit", name="config_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ManagerRegistry $doctrine, Config $config, Filesystem $filesystem): Response
    {
        $configInit = clone $config;
        $directories= json_decode($this->getParameter('hermes_list_templates'),true);
        $type_image= explode(',', $this->getParameter('hermes_list_type_image'));
        if(in_array($config->getCode(), $type_image)){
            $options['type_image'] = true;
        }
        $options['code_disabled'] = true;
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $options['code_disabled'] = false;
        }
        if('directory' == $config->getCode()){
            $options['value_choices'] = $directories;
        }
        $form = $this->createForm(ConfigType::class, $config, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager('config')->flush();
            try {
                $type = $request->attributes->get('config')->getType();
                return $this->redirectToRoute('config_index', ['type' => $type ]);
            } catch (\Exception $e) {                
                return $this->redirectToRoute('admin_index');
            }
        }
        $array = [
            'config' => $config,
            'form' => $form->createView(),
        ];
        $array = $this->mergeActiveConfig($doctrine, $array);
        return $this->render('admin/config/edit.html.twig', $array);
    }

    /**
     * @Route("/{id}", name="config_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ManagerRegistry $doctrine, Config $config): Response
    {
        if(!$this->isGranted('ROLE_SUPER_ADMIN')){
            $this->redirectToRoute('admin_index');
        }

        if ($this->isCsrfTokenValid('delete' . $config->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager('config');
            $entityManager->remove($config);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_index');
    }

//    public function updateForm($menus,$listform,$listform_init)
//    {
//
//        $forms_init = explode(',', $listform_init);
//        $forms = explode(',', $listform);
//        $forms_add = array_diff($forms,$forms_init);// add
//        $forms_delete =  array_diff($forms_init,$forms);//delete
//
//        foreach ($forms_delete as $form){
//            $sheet_form = $doctrine->getManager()->getRepository(Sheet::class)->findOneBy(['active' => true, 'name' => $form]);
//            if(!is_null($sheet_form)) {
//                $doctrine->getManager()->remove($sheet_form);
//            }
//        }
//
//        foreach ($forms_add as $form){
//            if (!array_key_exists(strtoupper($form), $menus)) {
//                $sheet_form = $doctrine->getManager()->getRepository(Sheet::class)->findOneBy(['active' => true, 'name' => $form]);
//                // Création sheet si le formulaire n'existe pas
//                if(is_null($sheet_form)){
//                    $newSheet = new Sheet();
//                    $newSheet->setCode($form);
//                    $newSheet->setName($form);
//                    $newSheet->setSlug($form);
//                    $doctrine->getManager()->persist($newSheet);
//                }
//            }
//        }
//        $doctrine->getManager()->flush();
//    }
}
