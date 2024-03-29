<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 09/03/20
 * Time: 15:46
 */

namespace App\Upload\Namer;

use App\Entity\Config\Config;
use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Symfony\Component\Filesystem\Filesystem;

class ConventionedDirectoryNamer implements DirectoryNamerInterface
{
    protected $directoryNamerLogger;
    protected $filesystem;
    protected $params;

    public function __construct(LoggerInterface $directoryNamerLogger, Filesystem $filesystem, ParameterBagInterface $params)
    {
        $this->directoryNamerLogger = $directoryNamerLogger;
        $this->filesystem = $filesystem;
        $this->params = $params;
    }

    public function directoryName($object, PropertyMapping $mapping): string
    {
        $notification = 'Upload image';
        try {
            $className = (new \ReflectionClass($object))->getShortName();
            if ('Sheet' == $className) {
                if (in_array('getCode', get_class_methods($object))) {
                    $path = $object->getCode() . '/';
                    return $path;
                }
            }
            if (in_array('getSheet', get_class_methods($object))) {
                if ('Menu' == $className) {
                    $path = $object->getSheet()->getCode() . '/' . $object->getCode() . '/' ;
                }else{
                    $path = $object->getSheet()->getCode() . '/' . $className . '/';
                }
                return $path;
            }
            if($object instanceof Post){
                $path = 'section';
                if(!is_null($object->getSection())){
                    $menu_code = $object->getSection()->getMenu()->getCode();
                    $section_id = $object->getSection()->getId();
                    if('' == $section_id){
    //                    dd($object);
                    }
    //                $path = $className.'/'.$menu_code.'/' ;
                    $path = 'section'. $section_id.'/'.$menu_code.'/';
                }
                return $path;
            }

            if($object instanceof Config){
                $path = $className.'/' ;
                return $path;
            }

            $path = $className.'/'.$object->getId().'/' ;

            return $path;
        } catch (\Exception $e) {
            $logContext = [
                'statut' => 'ko',
                'exception' => $e->getMessage(),
            ];
            $this->directoryNamerLogger->alert($notification, $logContext);
        }
    }

}


