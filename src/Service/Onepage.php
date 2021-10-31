<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 14/04/20
 * Time: 15:17
 */

namespace App\Service;

use App\Entity\Config\Config;
use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use App\Entity\Hermes\Sheet;
use App\Entity\Hermes\Template;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;



class Onepage
{

    private $em ;
    private $twig ;

    public function __construct(EntityManagerInterface $em, Environment $twig)
    {
        $this->em = $em;
        $this->twig = $twig;
    }

    public function addOnePageHmsLibre($config_manager, $config, $libres){

        $libre = Sheet::ONE_PAGE;
        $slug = strtolower($libre);

        try {
            $config_nav_bar =  $config_manager
                ->getRepository(Config::class, 'config')
                ->findOneBy(['code'=> 'nav_bar']);
            $config_nav_bar->setValue(Sheet::ONE_PAGE_LIBELLE);
            $sheet_code = 'new_sheet'.$libre;
            $sheet = $this->em
                ->getRepository(Sheet::class)
                ->findOneBy(['code'=> $sheet_code]);
            $template = $this->em
                ->getRepository(Template::class)
                ->findOneBy(['code'=> 'libre']);

            if(is_null($sheet)) {

                // add sheet
                $sheet = new Sheet();
                $sheet->setCode($sheet_code);
                $sheet->setName($libre);
                $sheet->setPosition(1);
                $sheet->setSummary('Menu ' . $libre);
                $sheet->setSlug($slug);

                // add menu
                $menu = new Menu();
                $menu->setCode($libre);
                $menu->setName($libre);
                $menu->setPosition(1);
                $menu->setSlug($slug);
                $menu->setSheet($sheet);

                // add section
                $section = new Section();
                $section->setName('section-'.str_replace(' ', '-', $libre));
                $section->setPosition(1);
                $section->setTemplateWidth(12);
                $section->setMenu($menu);
                $section->setTemplate($template);

            $this->em->persist($sheet);
            $this->em->persist($menu);
            $this->em->persist($section);
            }else{
                $menu = $this->em
                    ->getRepository(Menu::class)
                    ->findOneBy(['code'=> $libre]);
                $section = $this->em
                    ->getRepository(Section::class)
                    ->findOneBy(['name'=> 'section-'.str_replace(' ', '-', $libre)]);
            }


        $nbposts = count($this->em
            ->getRepository(Post::class)
            ->findAll());
            if(0 == $nbposts){
                $nbposts = 1;
            }
        $content = "";
        foreach ($libres as $key => $template_libre ){
            $position = $key + $nbposts ;
            // add Post
            $template_libre = str_replace('é', 'e',str_replace(' ', '-', str_replace('\'', '-', $template_libre['code'])));
            $content = $this->twig->render('admin/hermes/menu-libre/href.html.twig', ['template' => $template_libre]);
            $content .= $this->twig->render('admin/hermes/template-libre/'.$template_libre.'/index.html.twig', $config);

            $post = new Post();
            $post->setName($libre.$position);
            $post->setPosition($position);
            $post->setSection($section);
            $post->setContent($content);

            $this->em->persist($post);
        }

        $section_name = 'section-'.str_replace(' ', '-', "menu-".$libre);
        $onePageSection = $this->em
            ->getRepository(Section::class)
            ->findOneBy(['name'=> $section_name]);
        if(is_null($onePageSection)){
            $section = new Section();
            $section->setName($section_name);
            $section->setPosition(0);
            $section->setTemplateWidth(12);
            $section->setMenu($menu);
            $section->setTemplate($template);

            // add Post
            $menu_libre = $this->twig->render('admin/hermes/menu-libre/hms-1.html.twig', ['titre' => 'libre', 'templates' => $libres]);
            $post = new Post();
            $post->setName("menu-".$libre);
            $post->setPosition(0);
            $post->setSection($section);
            $post->setContent($menu_libre);

            $this->em->persist($section);
            $this->em->persist($post);
        }
        $config_manager->persist($config_nav_bar);

        }catch (\Exception $e){
            return ['warning' => $e->getMessage()];
        }

        $this->em->flush();
        $config_manager->flush();
        return ['info' => 'Page créée'];

    }

    public function getActiveConfig($config_manager)
    {
        /*
         * On récupère la configuration du site.
         */
        $configuration = $config_manager->getRepository(Config::class, 'Config')->findBy(['active' => true]);
        foreach ($configuration as $conf) {
            $config[$conf->getCode()] = $conf;
            if('bg_image' != $conf->getCode() && 'favicon' != $conf->getCode() && 'accueil' != $conf->getCode() && 'logo' != $conf->getCode()){
                $config_simple[$conf->getCode()] = $conf->getValue();
            }else{
                $config_simple[$conf->getCode()] = $conf;
            }
        }
        return $config_simple ;
    }

}
