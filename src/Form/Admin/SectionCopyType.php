<?php

namespace App\Form\Admin;

use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Section;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionCopyType extends AbstractNameBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['label_name']= 'global.name';
        $options['tooltip']= 'Nom';
        $options['active'] = false;
        $options['name'] = false;
        parent::buildForm($builder, $options);
        $builder
            ->add('menu', EntityType::class, [
                'required' => false,
                'class' => Menu::class,
                'label_format' => 'section.menu',
                'attr'=> ['class' => 'select2 custom-select select2 custom-select-lg mb-3'],
            ])
        ;
        $builder
            ->add('move', SubmitType::class, [
                'icon_before' => '<i class="fa fa-save"></i>',
                'label_html' => true,
                'label' => 'section.move'
            ]);
        $builder
            ->add('copy', SubmitType::class, [
                'icon_before' => '<i class="fa fa-copy"></i>',
                'label_html' => true,
                'label' => 'section.copy'
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
            'save' => false,
            'saveAndAdd' => false,
            'saveAndAddPost' => false,
            'saveAndAddSectionPost' => false,
        ]);
    }
}


