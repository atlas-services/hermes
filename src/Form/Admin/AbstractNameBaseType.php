<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractNameBaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['active']) {
            $builder
                ->add('active', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
                    'required' => false,
                    'label' => false,
                    'label_attr' => ['class' => 'switch-custom'],
                ]);
        }
        if ($options['name']) {
            $builder
                ->add('name', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                    'constraints' => $options['name_constraints'],
                    'required' => false, //$options['name_required'],
                    'label' => $options['label_name'],
                    'attr'=> ['class' => 'mb-3'],
                    'label_attr'=> [
                        'data-bs-toggle' => 'tooltip',
                        'data-placement'=> 'left',
                        'data-html' => 'true',
//                        'title'=> $options['title']
                    ]
                ]);
        };
        if ($options['save']) {
            $builder
                ->add('save', SubmitType::class, [
                'icon_before' => '<i class="fa fa-save"></i>',
                'label_html' => true,
                'label' => 'global.update'
            ]);
        }
        if ($options['saveAndAdd']) {
            $builder
                ->add('saveAndAdd', SubmitType::class, [
                    'icon_before' => '<i class="fa fa-save"></i> <i class="fa fa-plus-circle"></i>',
                    'label_html' => true,
                    'label' => $options['saveAndAddLabel'],
                ]);
        }
        if ($options['saveAndAddPost']) {
            $builder
                ->add('saveAndAddPost', SubmitType::class, [
                    'icon_before' => '<i class="fa fa-save"></i> <i class="fa fa-plus-circle"></i>',
                    'label_html' => true,
                    'label' =>  $options['saveAndAddLabel']
                ]);
        }
        if ($options['saveAndAddSectionPost']) {
            $builder
                ->add('saveAndAddSectionPost', SubmitType::class, [
                    'icon_before' => '<i class="fa fa-save"></i> <i class="fa fa-plus-circle"></i>',
                    'label_html' => true,
                    'label' =>  'menu.update_next_different'
                ]);
        }
        // if (isset($options['saveAndAddHermesListe'])) {
        //     if ($options['saveAndAddHermesListe']) {
        //         $builder
        //             ->add('saveAndAddHermesListe', SubmitType::class, [
        //                 'icon_before' => '<i class="fa fa-save"></i> <i class="fa fa-plus-circle"></i>',
        //                 'label_html' => true,
        //                 'label' =>  'menu.upload_hermes_dir'
        //             ]);
        //     }
        // }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'name_constraints' => [],
            'name_required' => false,
            'active' => true,
            'tooltip' => 'Nom',
            'save' => false,
            'saveAndAdd' => false,
            'saveAndAddLabel' => 'menu.update_next',
            'saveAndAddPost' => false,
            'saveAndAddSectionPost' => false,
            // 'saveAndAddHermesListe' => false,
        ]);
    }
}


