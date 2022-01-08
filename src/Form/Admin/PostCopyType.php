<?php

namespace App\Form\Admin;

use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostCopyType extends AbstractNameBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['label_name']= 'global.name';
        $options['tooltip']= 'Nom';
        $options['active'] = false;
        $options['name'] = false;
        parent::buildForm($builder, $options);
        $builder
            ->add('section', EntityType::class, [
                'required' => false,
                'class' => Section::class,
                'label_format' => 'post.section_target',
                'attr'=> ['class' => 'select2 custom-select select2 custom-select-lg mb-3'],
            ])
        ;
        $builder
            ->add('move', SubmitType::class, [
                'icon_before' => '<i class="fa fa-save"></i>',
                'label_html' => true,
                'label' => 'post.move'
            ]);
        $builder
            ->add('copy', SubmitType::class, [
                'icon_before' => '<i class="fa fa-copy"></i>',
                'label_html' => true,
                'label' => 'post.copy'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'save' => false,
            'saveAndAdd' => false,
            'saveAndAddPost' => false,
            'saveAndAddSectionPost' => false,
        ]);
    }
}


