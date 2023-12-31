<?php

namespace App\Form\Admin;

use App\Entity\Config\Config;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractBaseType
{
    const COLS = [
        '1/12' => '1',
        '2/12' => '2',
        '3/12' => '3',
        '4/12' => '4',
        '5/12' => '5',
        '6/12' => '6',
        '7/12' => '7',
        '8/12' => '8',
        '9/12' => '9',
        '10/12' => '10',
        '11/12' => '11',
        '12/12' => '12',
    ];

    const COLS_OFFSET = [
        '0/12' => '0',
        '1/12' => '1',
        '2/12' => '2',
        '3/12' => '3',
        '4/12' => '4',
        '5/12' => '5',
        '6/12' => '6',
        '7/12' => '7',
        '8/12' => '8',
        '9/12' => '9',
        '10/12' => '10',
        '11/12' => '11',
        '12/12' => '12',
    ];

    const COLS_ACCUEIL =[
        '10' => '10',
        '20' => '20',
        '30' => '30',
        '40' => '40',
        '50' => '50',
        '60' => '60',
        '70' => '70',
        '80' => '80',
        '90' => '90',
        '100' => '100',
    ];

    const DECIMAL = [
        '0.1' => '0.1',
        '0.2' => '0.2',
        '0.3' => '0.3',
        '0.4' => '0.4',
        '0.5' => '0.5',
        '0.6' => '0.6',
        '0.7' => '0.7',
        '0.8' => '0.8',
        '0.9' => '0.9',
        '1.0' => '1.0',
    ];

    const MARGES = [
        0 => 0 ,
        1 => 1 ,
        2 => 2 ,
        3 => 3 ,
        4 => 4 ,
        5 => 5 ,
    ];

    const TEXT_SIZE = [
        'h1' => 'h1' ,
        'h2' => 'h2' ,
        'h3' => 'h3' ,
        'h4' => 'h4' ,
        'h5' => 'h5' ,
        'h6' => 'h6' ,
        'display-1' => 'display-1' ,
        'display-2' => 'display-2' ,
        'display-3' => 'display-3' ,
        'display-4' => 'display-4' ,
        'display-5' => 'display-5' ,
        'display-6' => 'display-6' ,
    ];

    const TEMPLATE_BASE =[
        'front' => 'front',
    ];

    const FONT_FAMILY = [
        'Alfa Slab One' => 'Alfa Slab One',
        '\'Bai Jamjuree\', sans-serif' => '\'Bai Jamjuree\', sans-serif',
        ' Comic Sans MS, Comic Sans, cursive' => ' Comic Sans MS, Comic Sans, cursive',
        'Impact, fantasy' => 'Impact, fantasy',
        '\'Oswald\',Helvetica,Arial,Lucida,sans-serif' => '\'Oswald\',Helvetica,Arial,Lucida,sans-serif',
        '\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif' => ' \'Palatino Linotype\', \'Book Antiqua\', Palatino, serif',
        '\'Sofia\', sans-serif' => '\'Sofia\', sans-serif',
        '\'Snowburst One\', sans-serif' => '\'Snowburst One\', sans-serif',
        '\'The Antiqua B\', Georgia, Droid-serif, serif' => '\'The Antiqua B\', Georgia, Droid-serif, serif',
        'Verdana' => 'Verdana',
    ];

    const BTN_OUTLINE = [
        'btn-outline-primary' => 'btn-outline-primary',
        'btn-outline-secondary' => 'btn-outline-secondary',
        'btn-outline-success' => 'btn-outline-success',
        'btn-outline-danger' => 'btn-outline-danger',
        'btn-outline-warning' => 'btn-outline-warning',
        'btn-outline-info' => 'btn-outline-info',
        'btn-outline-light' => 'btn-outline-light',
        'btn-outline-dark' => 'btn-outline-dark',
        'btn-outline-link' => 'btn-outline-link',

    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, ['disabled' => $options['code_disabled']])
            ->add('type', ChoiceType::class, [
                'choices' => $options['type_choices'],
                'attr' => ['class' => 'select2 custom-select select2 custom-select-lg mb-3']
            ]);
        if (null != $options['value_choices']) {
            $builder
                ->add('value', ChoiceType::class, [
                    'choices' => $options['value_choices'],
                    'attr' => ['class' => 'select2 custom-select select2 custom-select-lg mb-3']
                ]);
        }
        $builder
            ->add('summary');
        if ($options['type_image']) {
            $builder
                ->add('imageFile', 'Vich\UploaderBundle\Form\Type\VichImageType', [
                    'required' => false,
                    'label' => 'global.image',
                    'translation_domain' => 'messages',
                    'download_uri' => false,
                ]);
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $choice = false;
            $data = $event->getData();
            $form = $event->getForm();
            $code = $data->getCode();
            switch ($code) {
                // accueil
                case 'accueil':
                    $choice = true;
                    $options = self::COLS_ACCUEIL;
                    break;
                // accueil
                case 'template':
                    $choice = true;
                    $options = self::TEMPLATE_BASE;
                    break;
                // nav_bar
                case 'nav_bar':
                    $choice = true;
                    $options = [
                        // 'one page' => 'one page',
                        'base' => 'base',
                        'left' => 'left',
                        'full' => 'full',
                    ];
                    break;
                // nav_bar
                case 'nav_espacement':
                    $choice = true;
                    $options = self::MARGES;
                    break;
                // nav_sub_menu_mx
                case 'nav_sub_menu_mx':
                    $choice = true;
                    $options = self::MARGES;
                    break;  
                 // nav_link_py
                case 'nav_link_py':
                    $choice = true;
                    $options = self::MARGES;
                    break;  
                 // nav_link_px
                 case 'nav_link_px':
                    $choice = true;
                    $options = self::MARGES;
                    break;  
                // nav_link_rounded
                case 'nav_link_rounded':
                    $choice = true;
                    $options = self::MARGES;
                    break;  
                // nav_bar
                case 'nav_link_border_bottom':
                    $choice = true;
                    $options = [
                        'Aucune séparation' => ' ',
                        'border-bottom' => 'border-bottom',
                    ];
                    break;
                // nav_menu_text_size
                case 'nav_menu_text_size':
                    $choice = true;
                    $options = self::TEXT_SIZE;
                    break;
                // nav_sub_menu_text_size
                case 'nav_sub_menu_text_size':
                    $choice = true;
                    $options = self::TEXT_SIZE;
                    break;
                // chevron
                case 'chevron':
                    $choice = true;
                    $options = [
                        'circle' => 'circle-',
                        'base' => '',
                    ];
                    break;
                // chevron opacity
                case 'chevron_accueil_opacity':
                    $choice = true;
                    $options = self::DECIMAL;
                    break;    
                // chevron opacity
                case 'chevron_opacity':
                    $choice = true;
                    $options = self::DECIMAL;
                    break;                      
                // chevron position
                case 'chevron_position':
                    $choice = true;
                    $options = [
                        'top'  => '0%',
                        'middle' => '50%' ,
                        'bottom' =>'95%',
                    ];
                    break;
                case 'chevron_right':
                    $choice = true;
                    $options = [
                        'right'  => '0%',
                        'middle' => '50%' ,
                        'left' =>'95%',
                    ];
                    break;                    
                // affiche_img_hermes
                case 'affiche_img_hermes':
                // affiche_logo_top
                case 'affiche_logo_top':
                // affiche_search
                case 'affiche_search':
                // affiche_footer
                case 'footer_affiche':
                case 'newsletter_active':
                case 'livredor_active':
                    $choice = true;
                    $options = [
                        'activer'  => true,
                        'desactiver' => false ,
                    ];
                    break;
                // logo
                case 'logo':
                case 'nav_height':
                    $choice = true;
                    foreach (range(0, 500, 10) as $number) {
                        $options[ $number."px"] = $number."px";
                    }
                    break;
                // nav_offset
                case 'nav_offset':
                    $choice = true;
                    $options = self::COLS_OFFSET;
                    break;
                // contact_bgcolor_btn
                case 'contact_bgcolor_btn':
                    $choice = true;
                    $options = self::BTN_OUTLINE;
                    break;
                // newsletter_bgcolor_btn
                case 'newsletter_bgcolor_btn':
                    $choice = true;
                    $options = self::BTN_OUTLINE;
                    break;        
                // livredor_bgcolor_btn
                case 'livredor_bgcolor_btn':
                    $choice = true;
                    $options = self::BTN_OUTLINE;
                    break;              
            }
            if ($choice) {
                $form->add('value', ChoiceType::class, [
                    'choices' => $options,
                    'attr' => ['class' => 'custom-select custom-select-lg mb-3']
                ]);
            }
            if(!$choice) {
                if ('color' == $code || strpos($code, 'color')) {
                    $form->add('value', ColorType::class, [
                        'required' => false,
                    ]);
                    $form->add('transparent', ChoiceType::class, [
                        'choices' =>  [
                            'transparent.no' => false,
                            'transparent.yes' => true ,
                        ],
                        'attr' => ['class' => 'custom-select custom-select-lg mb-3']
                    ]);
                } else {
                    if ('width' == $code || strpos($code, 'width')) {
                        $form->add('value', ChoiceType::class, [
                            'required' => false,
                            'choices' =>   self::COLS,
                            'attr' => ['class' => 'custom-select custom-select-lg mb-3']
                        ]);
                    }else{
                        if('font_family' == $code || strpos($code, 'font_family')){
                            $form->add('value', ChoiceType::class, [
                                'required' => false,
                                'choices' =>   self::FONT_FAMILY,
                                'attr' => ['class' => 'custom-select custom-select-lg mb-3']
                            ]);
                        }
                        else{
                            $form->add('value', TextType::class, [
                                'required' => false,
                            ]);
                        }
                    }
                }
            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if($data->transparent){
                $data->setValue('transparent');
            }
        });

    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
            'active' => true,
            'image_file' => true,
            'code_disabled' => true,
            'value_choices' => null,
            'type_image' => false,
            'type_choices' => [
                'head' => 'head',
                'générale' => 'site',
                'contenu' => 'content',
                'footer' => 'footer',
                'contact' => 'contact',
                'newsletter' => 'newsletter',
                'livredor' => 'livredor',
                'image' => 'image',
                'menu' => 'nav',
                'folio' => 'folio',
                'carousel' => 'carousel',
                'carte' => 'card',
                'modale' => 'modale',
                'réseaux sociaux' => 'network',
                'nd' => null,
            ],
            'nav_bar_choices' => [
                'base' => 'base',
                'left' => 'left',
            ],
        ]);
    }
}
