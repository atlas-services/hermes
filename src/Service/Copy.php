<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 14/04/20
 * Time: 15:17
 */

namespace App\Service;

use App\Entity\Hermes\Sheet;
use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use App\Entity\Hermes\Template;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;



class Copy
{

    private $em ;
    private $image ;
    private $cacheManager;
    private $dataManager;
    private $filterManager;
    private $filesystem;
    private $params;

    public function __construct(EntityManagerInterface $em, Image $image,CacheManager $cacheManager, DataManager $dataManager,
                                FilterManager $filterManager, Filesystem $filesystem, ParameterBagInterface $params
    )
    {
        $this->em = $em;
        $this->image = $image;
        $this->cacheManager  = $cacheManager;
        $this->dataManager   = $dataManager;
        $this->filterManager = $filterManager;
        $this->filesystem = $filesystem;
        $this->params = $params;
    }

    public function copySection(Section $section, Section $toSection, $copy = false){

        try {
            foreach($section->getPosts() as $post){
                $newPost = clone $post;
                $newPost->setSection($toSection);
                $this->em->persist($toSection);
                $this->em->persist($newPost);
            }

            $this->em->flush();
            // Ajout répertoire copié
            $menu_code = $toSection->getMenu()->getCode();
            $section_id = $toSection->getId();
            $newpath = 'section'. $section_id.'/'.$menu_code.'/';
            $hermes_path_content_image = $this->params->get('hermes_path_content_image');
            $newPath = $hermes_path_content_image.'/'.$newpath;
            if(!is_null($toSection->getPosts()->first()->getImageFile())){
                $this->filesystem->mirror($toSection->getPosts()->first()->getImageFile()->getPath(),$newPath);
            }

            if(!$copy){
                $this->em->remove($section);
                $this->em->flush();
            }
            return ['info' => 'Section copiée'];

        }catch (\Exception $e){
            return ['warning' => $e->getMessage()];
        }

    }

    public function copyPost(Post $post, Post $fromPost, $copy = false){

        try {
            $this->em->persist($post);

            if($copy){
                $this->em->persist($fromPost);
            }

            $this->em->flush();
            return ['info' => 'Post copié'];

        }catch (\Exception $e){
            return ['warning' => $e->getMessage()];
        }

    }

    public function handleHermesDir(Sheet $sheet){

        try {
            $menu = new Menu();
            $section = new Section();
            $template = new Template();

            $template = $this->em
                ->getRepository(Template::class)
                ->findOneBy(['code'=> $template::TEMPLATE_LISTE]);

            $template2 = $this->em
                ->getRepository(Template::class)
                ->findOneBy(['code'=> $template::TEMPLATE_MODALE]);

            $position = $this->em->getRepository(Menu::class)->getMaxPosition();

            $menu->setName('menu'.$sheet->getName());
            $menu->setCode($sheet->getName());
            $menu->setSlug($sheet->getName());
            $menu->setSheet($sheet);
            $menu->setPosition($position);
            $section->setName('section'.$sheet->getName());
            $section->setMenu($menu);
            $section->setTemplate($template);
            $section->setTemplate2($template2);
            $section->setTemplateWidth(9);
            $section->setTemplate2Width(4);
            $this->em->persist($section);
            $this->em->persist($menu);
            $this->em->persist($sheet);
            $this->em->flush();
            $dir = 'section'.$section->getId().'/'.$menu->getCode().'/';
            $files = $this->image->getListHermesDirFiles($dir);
            foreach ($files as $key => $path){
                $nb = $key + 1;
                $pos = strpos($path, 'public') +7;
                // $filter_path = substr($path, $pos);
                // $cache_path = $this->filter($filter_path, 'app_fixed_filter_bd_154', 700 , 328 );
                // $file = new File($cache_path);
                $file = new File($path);
                $post = new Post();
                $post->setName('Image'.$nb);
                $post->setSection($section);
                $post->setImageFile($file);
                $post->setFileName($file->getFilename());
                $this->em->persist($post);
            }
            $this->em->flush();
            return ['info' => 'Post copié'];

        }catch (\Exception $e){
            return ['warning' => $e->getMessage()];
        }

    }


    public function handleUploadedDir(Section $section, $uploaded_dir){

        try {

            $template = $this->em
                ->getRepository(Template::class)
                ->findOneBy(['code'=> Template::TEMPLATE_LISTE]);

            $template2 = $this->em
                ->getRepository(Template::class)
                ->findOneBy(['code'=> $template::TEMPLATE_MODALE]);

            $dir = 'section'.$section->getId().'/'.$section->getMenu()->getCode().'/';
            $files = $this->image->getListSelectedDirFiles($uploaded_dir, $dir);
            foreach ($files as $key => $path){
                $nb = $key + 1;
                $pos = strpos($path, 'public') +7;
                $file = new File($path);
                $post = new Post();
                $post->setName('Image'.$nb);
                $post->setSection($section);
                $post->setImageFile($file);
                $post->setFileName($file->getFilename());
                $this->em->persist($post);
            }
            $this->em->flush();
            return ['info' => 'Post copié'];

        }catch (\Exception $e){
            return ['warning' => $e->getMessage()];
        }

    }



    public function filter($path, $filter, int $width, int $height) {

        if (!$this->cacheManager->isStored($path, $filter)) {
            $binary = $this->dataManager->find($filter, $path);

            $filteredBinary = $this->filterManager->applyFilter($binary, $filter, [
                'filters' => [
                    'thumbnail' => [
                        'size' => [$width, $height]
                    ]
                ]
            ]);
            $this->cacheManager->store($filteredBinary, $path, $filter);
        }
        $url_image = $this->cacheManager->resolve($path, $filter);
        $pos = strpos($url_image, 'data') ;
        $filter_path = substr($url_image, $pos);
        $image_path = getcwd().'/'.$filter_path;
        return $image_path;
//        return new RedirectResponse($this->cacheManager->resolve($path, $filter), Response::HTTP_MOVED_PERMANENTLY);
    }



    public function copyLocale($locale){

        try {
//            $contactLocale = $this->em->getRepository(Sheet::class)->findOneBy(['locale' => $locale, 'slug' => 'contact']);
//            if(!is_null($contactLocale)){
//                $this->em->remove($contactLocale);
//                $this->em->flush();
//            }
//            $sheets = $this->em->getRepository(Sheet::class)->findBy(['locale' => $locale]);

//            if(!empty($sheets)){
//                foreach ($sheets as $sheet){
//                    $this->em->remove($sheet);
//                }
//                $this->em->flush();
////                return ['info' => 'langue existe déjà'];
//            }
            $sheets = $this->em->getRepository(Sheet::class)->findAll();
            foreach ($sheets as $sheet){
                $exists = $this->em->getRepository(Sheet::class)->findOneBy(['locale' => $locale, 'referenceName' => $sheet->getReferenceName()]);
                if(is_null($exists)){
                    $sheet->setReferenceName($sheet->getName());
                    $sheetLocale = clone $sheet;
                    $sheetLocale->setlocale($locale);
                    $sheetLocale->setReferenceName($sheet->getName());
                    $sheetLocale->setName($sheet->getName(). '-'. $locale);
                    $sheetLocale->setSlug($sheetLocale->getName());
                    $this->em->persist($sheet);
                    $this->em->persist($sheetLocale);
                }
            }
            $this->em->flush();

            $menus = $this->em->getRepository(Menu::class)->findAll();
            foreach ($menus as $menu){
                $exists = $this->em->getRepository(Menu::class)->findOneBy(['locale' => $locale, 'referenceName' => $menu->getReferenceName()]);
                if(is_null($exists)){
                    $menu->setReferenceName($menu->getName());
                    $menuLocale = clone $menu;
                    $sheetLocale = $this->em->getRepository(Sheet::class)->findOneBy(['locale' => $locale, 'referenceName' => $menu->getSheet()->getReferenceName() ]);
                    $menuLocale->setlocale($locale);
                    $menuLocale->setReferenceName($menu->getName());
                    $menuLocale->setSheet($sheetLocale);
                    $menuLocale->setName($menu->getName(). '-'. $locale);
                    $menuLocale->setSlug($menuLocale->getName());
                    $this->em->persist($menu);
                    $this->em->persist($menuLocale);
                }
            }
            $this->em->flush();

            $sections = $this->em->getRepository(Section::class)->findAll();
            foreach ($sections as $section){
                $sectionLocale = clone $section;
                $this->copySection($section, $sectionLocale, true );
                $menuLocale = $this->em->getRepository(Menu::class)->findOneBy(['locale' => $locale, 'referenceName' => $section->getMenu()->getReferenceName() ]);
                $sectionLocale->setMenu($menuLocale);
                $sectionLocale->setName($sectionLocale->getName(). '-'. $locale);
                $this->em->persist($sectionLocale);
            }
            $this->em->flush();

            return ['info' => 'nouvelle langue copiée'];

        }catch (\Exception $e){
            return ['warning' => $e->getMessage()];
        }

    }

}
