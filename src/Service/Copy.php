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
use Symfony\Component\HttpFoundation\File\File;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;



class Copy
{

    private $em ;
    private $cacheManager;
    private $dataManager;
    private $filterManager;

    public function __construct(EntityManagerInterface $em,CacheManager $cacheManager, DataManager $dataManager, FilterManager $filterManager)
    {
        $this->em = $em;
        $this->cacheManager  = $cacheManager;
        $this->dataManager   = $dataManager;
        $this->filterManager = $filterManager;
    }

    public function copySection(Section $section, Section $fromSection, $copy = false){

        try {
            $this->em->persist($section);

            if($copy){
                foreach($section->getPosts() as $post){
                    $oldPost = clone $post;
                    $oldPost->setSection($fromSection);
                    $this->em->persist($fromSection);
                    $this->em->persist($oldPost);
                }
            }

            $this->em->flush();
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

    public function handleHermesDir(Sheet $sheet, Image $image){

        try {
            $menu = new Menu();
            $section = new Section();
            $template = new Template();

            $template = $this->em
                ->getRepository(Template::class)
                ->findOneBy(['code'=> $template::TEMPLATE_LISTE]);

            $menu->setName($sheet->getName());
            $menu->setCode($sheet->getName());
            $menu->setSlug($sheet->getName());
            $menu->setSheet($sheet);
            $section->setName($sheet->getName());
            $section->setMenu($menu);
            $section->setTemplate($template);
            $files = $image->getListHermesDirFiles($sheet->getName());
            foreach ($files as $key => $path){
                $pos = strpos($path, 'public') +7;
                $nb = $key + 1;
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

            $this->em->persist($section);
            $this->em->persist($menu);
            $this->em->persist($sheet);
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
