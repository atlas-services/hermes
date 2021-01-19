<?php

namespace App\Form\Admin;

use App\Entity\Config;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractBaseType
{
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
//            else{
//                $builder
//                    ->add('value',TextType::class, [
//                        'required'   => false,
//                    ] );
//            }
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
            $options_font_family = [
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
            $options_pct = [
                '10%' => '10%',
                '20%' => '20%',
                '30%' => '30%',
                '40%' => '40%',
                '50%' => '50%',
                '60%' => '60%',
                '70%' => '70%',
                '80%' => '80%',
                '90%' => '90%',
                '100%' => '100%',
            ];
            $options_pct_value = [
                '10%' => '10',
                '20%' => '20',
                '30%' => '30',
                '40%' => '40',
                '50%' => '50',
                '60%' => '60',
                '70%' => '70',
                '80%' => '80',
                '90%' => '90%',
                '100%' => '100',
            ];
            $options_template_value = [
                'one_page_1' => 'one_page_1',
                'one_page_2' => 'one_page_2',
                'front' => 'front',
            ];
            switch ($code) {
                // accueil
                case 'accueil':
                    $choice = true;
                    $options = $options_pct_value;
                    break;
                // accueil
                case 'template':
                    $choice = true;
                    $options = $options_template_value;
                    break;
                // nav_bar
                case 'nav_bar':
                    $choice = true;
                    $options = [
                        'base' => 'base',
                        'left' => 'left',
                        'full' => 'full',
                        'none' => 'none',
                    ];
                    break;
                // nav_bar
                case 'nav_link_border_bottom':
                    $choice = true;
                    $options = [
                        'Aucune séparation' => ' ',
                        'border-bottom' => 'border-bottom',
                    ];
                    break;
                // chevron
                case 'chevron':
                    $choice = true;
                    $options = [
                        'circle' => 'circle-',
                        'base' => '',
                    ];
                    break;
                // chevron
                case 'chevron_position':
                    $choice = true;
                    $options = [
                        'top'  => '0%',
                        'middle' => '50%' ,
                        'bottom' =>'95%',
                    ];
                    break;
                // affiche_img_hermes
                case 'affiche_img_hermes':
                    $choice = true;
                    $options = [
                        'true'  => true,
                        'false' => false ,
                    ];
                    break;
                // affiche_footer
                case 'footer_affiche':
                    $choice = true;
                    $options = [
                        'true'  => true,
                        'false' => false ,
                    ];
                    break;
            }
            if ($choice) {
                $form->add('value', ChoiceType::class, [
                    'choices' => $options,
                    'attr' => ['class' => 'select2 custom-select select2 custom-select-lg mb-3']
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
                        'attr' => ['class' => 'select2 custom-select select2 custom-select-lg mb-3']
                    ]);
                } else {
                    if ('width' == $code || strpos($code, 'width')) {
                        $form->add('value', ChoiceType::class, [
                            'required' => false,
                            'choices' =>   $options_pct,
                            'attr' => ['class' => 'select2 custom-select select2 custom-select-lg mb-3']
                        ]);
                    }else{
                        if('font_family' == $code || strpos($code, 'font_family')){
                            $form->add('value', ChoiceType::class, [
                                'required' => false,
                                'choices' =>   $options_font_family,
                                'attr' => ['class' => 'select2 custom-select select2 custom-select-lg mb-3']
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
                'contact' => 'contact',
                'ecommerce' => 'ecommerce',
                'générale' => 'site',
                'image' => 'image',
                'menu' => 'nav',
                'contenu' => 'content',
                'folio' => 'folio',
                'carousel' => 'carousel',
                'carte' => 'card',
                'modale' => 'modale',
                'footer' => 'footer',
                'réseaux sociaux' => 'network',
                'formulaires' => 'form',
                'nd' => null,
            ],
            'nav_bar_choices' => [
                'base' => 'base',
                'left' => 'left',
            ],
        ]);
    }
}
