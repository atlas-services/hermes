<?php

namespace App\Form\Admin;

use App\Entity\Hermes\Section;

use App\Entity\Hermes\Template;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
class SectionType extends AbstractNameBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['label_name']= 'global.name';
        $options['tooltip']= 'Nom';
        $options['active'] = false;
        $options['name'] = false;
        parent::buildForm($builder, $options);
        $builder
            ->add('template', 'Symfony\Bridge\Doctrine\Form\Type\EntityType',
                [
                    'class'=> Template::class,
                    'required' => true,
                    'label' => 'section.template',
                    'attr'=> ['class' => 'select2 custom-select custom-select-lg mb-3']
                ])
            // ->add('position','Symfony\Component\Form\Extension\Core\Type\NumberType', [
            //     'required' => false,
            //     'label' => 'global.position',
            // ])
            ->add('position', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'required' => false,
                'attr' => [
                    'min' => 0,
                    'max' => 999,                       
                    'class' => 'custom-select custom-select-lg mb-3 ',
                    'label' => 'global.position',
                ],
                'choices' => range(0, 999),
            ])
            ->add('posts', CollectionType::class, [
                'entry_type' => PostType::class,
                'prototype'=> true,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'entry_options' => ['label' => false, 'saveAndAddSectionPost' => true],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
            'save' => false,
            'saveAndAdd' => false,
            'saveAndAddLabel' => 'menu.update_next',
            'saveAndAddPost' => false,
            'saveAndAddSectionPost' => false,
        ]);
    }
}


