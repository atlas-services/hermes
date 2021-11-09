<?php

declare(strict_types=1);

namespace App\Form\Traits;

trait ImageFileTrait
{


    public function getImafileOptions(): ?array
    {
        $options =  [
            'required' => false,
            'label' => 'global.image',
            'translation_domain'=> 'messages',
            'download_uri' => false,
            'attr'=> ['class'=> 'col-lg-12 btn hms-bg-color1 '],
        ];
        return $options;
    }

}