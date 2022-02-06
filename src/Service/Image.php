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

    public function shuffle($src){
        $basedir = getcwd()."/img/hermes/images";
        $target = $basedir."/$src";

        $images = glob($target."/*.jpg");
        //reset img*
        foreach ($images as $filename) {
            if(strpos($filename,$target.'/img') !== false ){
                $this->filesystem->remove($filename);
            }
        }

        $images = glob($target."/*.jpg");
        shuffle($images);

        foreach ($images as $key => $filename) {
            $n = $key + 1;
            $this->resize($filename, $target.'/img'.$n.'.jpg', $src, 100);
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
