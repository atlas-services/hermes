<?php

namespace App\Form\Admin;

use App\Entity\Block;

use App\Entity\BlockPost;
use App\Entity\Template;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
class BlockType extends AbstractNameBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('active')
            ->add('name','Symfony\Component\Form\Extension\Core\Type\TextType', [
                'required' => true,
                'label' => 'block.name',
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'active' => true,
            'name' => true,
            'name_constraints' => true,
            'label_name' => true,
            'data_class' => Block::class,
            'save' => true,
            'saveAndAdd' => false,
            'saveAndAddLabel' => 'menu.update_next',
            'saveAndAddPost' => true,
            'saveAndAddSectionPost' => false,
        ]);
    }
}


