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
            if($copy){
                foreach($section->getPosts() as $post){
//                    $path = $post->getImageFile()->getPath();
                    $newPost = clone $post;
                    $newPost->setSection($toSection);
                    $this->em->persist($toSection);
                    $this->em->persist($newPost);
                }
//                die;
            }else{
                $this->em->persist($section);
            }

            $this->em->flush();
            // Ajout répertoire copié
            $menu_code = $toSection->getMenu()->getCode();
            $section_id = $toSection->getId();
            $newpath = 'section'. $section_id.'/'.$menu_code.'/';
            $hermes_path_content_image = $this->params->get('hermes_path_content_image');
            $newPath = $hermes_path_content_image.'/'.$newpath;
            if(!is_null($post->getImageFile())){
                $this->filesystem->mirror($post->getImageFile()->getPath(),$newPath);
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

            $menu->setName('menu'.$sheet->getName());
            $menu->setCode($sheet->getName());
            $menu->setSlug($sheet->getName());
            $menu->setSheet($sheet);
            $section->setName('section'.$sheet->getName());
            $section->setMenu($menu);
            $section->setTemplate($template);
            $this->em->persist($section);
            $this->em->persist($menu);
            $this->em->persist($sheet);
            $this->em->flush();
            $dir = 'section'.$section->getId().'/'.$menu->getCode().'/';
            $files = $this->image->getListHermesDirFiles($dir);
            foreach ($files as $key => $path){
                $nb = $key + 1;
                $pos = strpos($path, 'public') +7;
                $filter_path = substr($path, $pos);
                $cache_path = $this->filter($filter_path, 'app_fixed_filter_bd_154', 700 , 328 );
                $file = new File($cache_path);
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

}
