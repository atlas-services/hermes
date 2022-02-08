<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 14/04/20
 * Time: 15:17
 */

namespace App\Service;


use Symfony\Component\Filesystem\Filesystem;



class Image
{
    protected $filesystem;
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

    }

    public function shuffle(){

        $list= ['carre', '1620x1080'];
        $currentDir = getcwd()."/img/hermes/images";
        $baseDir =  $currentDir."/base";
        $images_base = glob($baseDir."/*.jpg");

        if([] == $images_base) {
            $this->filesystem->mirror($currentDir, $baseDir);
            $images_base = glob($baseDir."/*.jpg");
        }

        shuffle($images_base);

        foreach ($list as $src) {
            $target = $currentDir."/$src";

            $images = glob($target."/img*.jpg");
            if([] == $images){
                $this->filesystem->mkdir($target);
                foreach ($images_base as $key => $filename) {
                    $n = $key + 1;
                    $this->resize($filename, $target.'/img'.$n.'.jpg', $src, 100);
                }
                $images = glob($target."/img*.jpg");
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
}
