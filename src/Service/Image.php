<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 14/04/20
 * Time: 15:17
 */

namespace App\Service;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Image
{
    const HERMES_DIR='hermes';
    protected $filesystem;
    protected $parameterBag;
    public function __construct(Filesystem $filesystem, ParameterBagInterface $parameterBag)
    {
        $this->filesystem = $filesystem;
        $this->parameterBag = $parameterBag;
    }

    public function shuffle(){
        $list= ['carre', '1620x1080'];
//        $currentDir = getcwd()."/public/img/hermes/images";
        $currentDir = $this->parameterBag->get('hermes_path_hermes_images');
        $baseDir =  $currentDir."/base";
        $images_base = glob($baseDir."/*.jpg");

        if([] == $images_base) {
            $this->filesystem->mirror($currentDir, $baseDir);
            $images_base = glob($baseDir."/*.*");
        }

        shuffle($images_base);

        foreach ($list as $src) {
            $target = $currentDir."/$src";

            $images = glob($target."/img*.*");
            if([] == $images){
                $this->filesystem->mkdir($target);
                foreach ($images_base as $key => $filename) {
                    $n = $key + 1;
                    $this->resize($filename, $target.'/img'.$n.'.jpg', $src, 100);
                }
                $images = glob($target."/img*.*");
            }
        }

    }

    public function resize($source, $target, $src, $quality){

        $imageSize = getimagesize($source) ;
        $imageRessource= imagecreatefromjpeg($source) ;

        switch ($src){
            case 'carre':
                $width = 1080;
                $height = 1080;
                $new_width = 1080;
                $new_height = 1080;
                $src_x = 300;
                $src_y = 0;
                $dst_x = 0;
                $dst_y = 0;
                break;
            case '1620x1080':
                $width = 1620; //$imageSize[0]
                $height = 1080; // $imageSize[1]
                $new_width = 1620;
                $new_height = 1080;
                $src_x = 0;
                $src_y = 0;
                $dst_x = 0;
                $dst_y = 0;
                break;
        }

        $imageFinal = imagecreatetruecolor($new_width, $new_height) ;
        $final = imagecopyresampled($imageFinal, $imageRessource, $dst_x, $dst_y, $src_x, $src_y, $new_width, $new_height, $width, $height) ;
        imagejpeg($imageFinal, $target, $quality) ;
    }

    // Charge les images du repertoire "hermes" pour les listes
    public function getListHermesDirFiles($dir){
        $listDir = getcwd().'/'.$this->parameterBag->get('hermes_path_content_images').'/'.$dir;
        $originDir = $this->getContentHermesDirFiles();
        $this->filesystem->mirror($originDir, $listDir);
//        $images_base = glob($listDir."/*.jpg");
        $images_base = glob($listDir."/*.*");
        return $images_base;
    }

    // Charge les images du repertoire "hermes" pour les content des Post
    public function getContentHermesDirFiles($dir = self::HERMES_DIR){
        $contentDir = getcwd().'/'.$this->parameterBag->get('hermes_path_content_image_post').'/'.$dir;

        return $contentDir;
    }
}
