<?php

namespace App\Form\Admin\Libre;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TemplateLibreHmsCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('templates',CollectionType::class, [
                'entry_type' => TemplateLibreHmsType::class,
                'entry_options' => ['label' => false, ],
                 'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class, [
                'icon_before' => '<i class="fa fa-save"></i>',
                'label_html' => true,
                'label' => 'global.update'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          ]);
    }
}


