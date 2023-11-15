<?php

namespace App\EventSubscriber;

use FM\ElfinderBundle\Event\ElFinderPostExecutionEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem ;
use Symfony\Component\Finder\Finder;

class ElfinderSubscriber implements EventSubscriberInterface
{

    protected $parameterBag;
    protected $filesystem;

    public function __construct(Filesystem $filesystem, ParameterBagInterface $parameterBag)
    {
        $this->filesystem = $filesystem;
        $this->parameterBag = $parameterBag;

    }

    public function onElFinderPostExecutionEvent(ElFinderPostExecutionEvent $event): void
    {

        $request = $event->getRequest();
        $cmd = $request->query->get('cmd') ;


        if( 'ls' == $cmd){
            $project_dir = $this->parameterBag->get('kernel.project_dir');
            $listDirEntity = $project_dir.'/public/'.$this->parameterBag->get('hermes_path_content_images').'/';
            $listDirContent = $project_dir.'/public/'.$this->parameterBag->get('hermes_path_content_image_post').'/';
            $finder = new Finder();
            $filesEntity = $finder->files()->in($listDirEntity)->size('> 2M');
            $filesContent = $finder->files()->in($listDirContent)->size('> 2M');

            foreach($filesContent as $file){
                $filename = $file->getFilename();
                $path = $file->getPath();
                $realpath = $file->getRealPath();

                list($width, $height) = getimagesize($realpath);
                $ratio = 1; 
                $newWidth = $width;
                if(0 != $width){
                    $newWidth = 2000;
                    $ratio = $newWidth/$width;                    
                }

                $newHeight = $height * $ratio;

                // Load
                try {
                    $thumb = imagecreatetruecolor($newWidth, $newHeight);
                    $source = imagecreatefromjpeg($realpath);
                } catch (\Exception $ex) {
                    $source = false;
                    $image_data = file_get_contents($realpath);
                    try {
                        $source = imagecreatefromstring($image_data);
                    } catch (\Exception $ex) {
                        $source = false;
                    }               
                }

                if ($source !== false){
                    // Resize
                    $return = imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);


                    $newImg = $path. '/new'.$filename ;

                    imagejpeg($thumb, $newImg);

                    $this->filesystem->rename($newImg, $realpath, true);
                }
 
            }
        }



    }




    public static function getSubscribedEvents(): array
    {
        return [
            // ElFinderPreExecutionEvent::class => 'onElFinderPreExecutionEvent',
            ElFinderPostExecutionEvent::class => 'onElFinderPostExecutionEvent',
        ];
    }
    
}
