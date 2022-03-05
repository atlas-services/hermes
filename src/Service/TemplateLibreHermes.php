<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 14/04/20
 * Time: 15:17
 */

namespace App\Service;

class TemplateLibreHermes
{
    const TEMPLATE_LIBRE_HERMES = 'template_libre_hermes';
    const TEMPLATE_LIBRE_HERMES_DIR = 'admin/hermes/template-libre';
    const MODELES = ['entete', 'image', 'bloc'];

    public function getListByType($configurations){
        $array=[];
        foreach ($configurations as $code => $template){
            if( 'hms-' == substr($code, 0, 4) ){
                $array[] = $template;
            }
        }
        return $array;

    }
    public function getList($configurations){
        $templates = [];
        $modeles = self::MODELES;
        foreach ($configurations as $key=>$value){
            if(self::TEMPLATE_LIBRE_HERMES == $key ){
                foreach ($modeles as $modele){
                    $array = $this->getListByType($value[$modele]);
                    $templates['sections'][$modele] =  $array;
                }
            }
        }
        return $templates;

    }

}
