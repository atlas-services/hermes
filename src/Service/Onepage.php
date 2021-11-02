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

        try {
            $onepage = Sheet::ONE_PAGE;

            $this->setOnePageConfig($config_manager);
            $section = $this->prepareOnepageSection(Template::TEMPLATE_LIBRE);
            $template = $section->getTemplate();
            $menu = $section->getMenu();

            // add Posts
            $nbposts = $this->getOnepagePostNumber();
            foreach ($libres as $key => $template_libre ){
                $position = $key + $nbposts ;
                // add Post
                $template_libre = str_replace('é', 'e',str_replace(' ', '-', str_replace('\'', '-', $template_libre['code'])));
                $content = $this->twig->render('admin/hermes/menu-libre/href.html.twig', ['template' => $template_libre]);
                $content .= $this->twig->render('admin/hermes/template-libre/'.$template_libre.'/index.html.twig', $config);

                $post = new Post();
                $post->setName($onepage.$position);
                $post->setPosition($position);
                $post->setSection($section);
                $post->setContent($content);

                $this->em->persist($post);
            }

            $section_menu_name = 'section-'.str_replace(' ', '-', "menu-".$onepage);
            $onePageSection = $this->em
                ->getRepository(Section::class)
                ->findOneBy(['name'=> $section_menu_name]);
            if(is_null($onePageSection)) {
                $section = new Section();
                $section->setName($section_menu_name);
                $section->setPosition(0);
                $section->setTemplateWidth(12);
                $section->setMenu($menu);
                $section->setTemplate($template);

                // add Post
                $menu_libre = $this->twig->render('admin/hermes/menu-libre/hms-1.html.twig', ['titre' => 'libre', 'templates' => $libres]);
                $post = new Post();
                $post->setName("menu-" . $onepage);
                $post->setPosition(0);
                $post->setSection($section);
                $post->setContent($menu_libre);

                $this->em->persist($section);
                $this->em->persist($post);
            }

            $this->em->flush();
            return ['info' => 'Page créée'];

        }catch (\Exception $e){
            return ['warning' => $e->getMessage()];
        }

    }

    public function getOnePagePostByTemplateType($type= Template::TEMPLATE_LIBRE){

        try{
            $onepage = Sheet::ONE_PAGE;
            $section = $this->prepareOnepageSection($type);

            // ne pas persister post!
            $position = 1;
            $post = new Post();
            $post->setName($onepage.$position);
            $post->setPosition($position);
            $post->setSection($section);

            return $post ;

        }catch (\Exception $e){
            return ['warning' => $e->getMessage()];
        }

    }

    private function prepareOnepageSection($type= Template::TEMPLATE_LIBRE){

        $onepage = Sheet::ONE_PAGE;
        $slug = strtolower($onepage);

        try {
            $template = $this->em
                ->getRepository(Template::class)
                ->findOneBy(['code'=> $type]);

            $sheet = $this->em
                ->getRepository(Sheet::class)
                ->findOneBy(['code'=> $onepage]);

            if(is_null($sheet)) {
                // add sheet
                $sheet = new Sheet();
                $sheet->setCode($onepage);
                $sheet->setName($onepage);
                $sheet->setPosition(1);
                $sheet->setSummary('Menu ' . $onepage);
                $sheet->setSlug($slug);

                // add menu
                $menu = new Menu();
                $menu->setCode($onepage);
                $menu->setName($onepage);
                $menu->setPosition(1);
                $menu->setSlug($slug);
                $menu->setSheet($sheet);

                $this->em->persist($sheet);
                $this->em->persist($menu);
            }
            else{
                $menu = $this->em
                    ->getRepository(Menu::class)
                    ->findOneBy(['code'=> $onepage]);
            }

            // add section
            $num_section = $this->getOnepageSectionNumber($menu);
            $section_name = 'section-'.str_replace(' ', '-', $onepage).'-'.$num_section;
            $section = new Section();
            $section->setName($section_name);
            $section->setPosition($num_section);
            $section->setTemplateWidth(12);
            $section->setMenu($menu);
            $section->setTemplate($template);

            $this->em->persist($section);

        }catch (\Exception $e){
            return ['warning' => $e->getMessage()];
        }

        return $section ;

    }

    private function getOnepageSectionNumber($menu){
        $nbsections = count($menu->getSections());
        if(0 == $nbsections){
            return 1;
        }
        return $nbsections;
    }

    private function getOnepagePostNumber(){
        $nbposts = count($this->em
            ->getRepository(Post::class)
            ->findAll());
        if(0 == $nbposts){
            return 1;
        }
        return $nbposts;
    }

    public function setOnePageConfig($config_manager){
        $config_nav_bar =  $config_manager
            ->getRepository(Config::class, 'config')
            ->findOneBy(['code'=> 'nav_bar']);
        $config_nav_bar->setValue(Sheet::ONE_PAGE_LIBELLE);
        $config_manager->persist($config_nav_bar);
        $config_manager->flush();


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
