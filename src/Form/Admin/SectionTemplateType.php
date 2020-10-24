<?php

namespace App\Form\Admin;

use App\Entity\Menu;
use App\Entity\Section;

use App\Entity\Template;
use App\Repository\TemplateRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SectionTemplateType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['active'] = false;
        $options['name'] = false;
        $options['tooltip']= 'Nom';
        parent::buildForm($builder, $options);
        $builder
            ->add('template', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'class'=> Template::class,
                'query_builder' => function (TemplateRepository $er)  use ($options) {
                if($options['full_template']){
                    return $er->getQbTemplates();
                }
                    return $er->getQbInitTemplates();
                },
                'attr'=> ['class' => 'select2 custom-select custom-select-lg mb-3 ']
            ])
            ->add('posts', CollectionType::class, [
                'entry_type' => PostType::class,
                'prototype'=> true,
                'prototype_name' => 'post',
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'label'=> false,
                'entry_options' => ['label' => false, 'active'=> false, 'position'=>false, 'name'=>true, 'content'=>$options['content'], 'save_visibility' => false, 'save' => false, 'saveAndAdd' => false, 'saveAndAddPost' => false, 'saveAndAddSectionPost' => false,],
            ])
        ;
        if($options['menu']){
            $builder
                ->add('menu', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                    'class'=> Menu::class,
                    'attr'=> ['class' => 'select2 custom-select custom-select-lg mb-3 ']
                ]);
        }
        if($options['position']){
            $builder
                ->add('position');
        }
        if ($options['save_visibility']) {
            $builder
                ->add('saveAndAddPost', SubmitType::class, [
                    'icon_before' => '<i class="fa fa-save"></i> <i class="fa fa-plus-circle"></i>',
                    'label_html' => true,
                    'label' => 'menu.update_next'
                ])
                ->add('saveAndAddSectionPost', SubmitType::class, [
                    'icon_before' => '<i class="fa fa-save"></i> <i class="fa fa-plus-circle"></i>',
                    'label_html' => true,
                    'label' => 'menu.update_next_different'
                ])
                ->add('save', SubmitType::class, [
                    'icon_before' => '<i class="fa fa-save"></i>',
                    'label_html' => true,
                    'label' => 'global.update'
                ]);
        }

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            [$this, 'onPostSubmitData']
        );
    }

    public function onPostSubmitData(FormEvent $event)
    {
        $section= $event->getData();
        $form= $event->getForm();
        $section->setName($form->getViewData()->getTemplate()->getName().'-'.$section->getMenu()->getName());
        $section->setTemplate($form->getViewData()->getTemplate());
        $event->setData($section);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
            'save_visibility' => true,
            'content' => true,
            'menu' => false,
            'full_template' => true,
            'name_constraints' => [],
            'position' => false,
            'saveAndAddSectionPost' => false,
        ]);
    }
}


