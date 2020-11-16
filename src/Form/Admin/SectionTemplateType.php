<?php

namespace App\Form\Admin;

use App\Entity\Menu;
use App\Entity\Post;
use App\Entity\Remote;
use App\Entity\Section;

use App\Entity\Template;
use App\Repository\TemplateRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SectionTemplateType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['active'] = false;
        $options['name'] = false;
        $options['tooltip']= 'Nom';
//        parent::buildForm($builder, $options);
        if($options['position']){
            $builder
                ->add('position', 'Symfony\Component\Form\Extension\Core\Type\NumberType', [
                    'required'=> false,
                    'attr'=> ['class' => 'select2 custom-select custom-select-lg mb-3 ']
                ]);
        }
        if($options['remote_pictures']){
            $builder
                ->add('remote', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                    'class'=> Remote::class,
                    'required'=> false,
                    'placeholder' => '-- pas d\'image distante --',
                    'attr'=> ['class' => 'select2 custom-select custom-select-lg mb-3 ']
                ]);
        }
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
            ->add('templateWidth', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'choices' => [
                    '10%' => '10',
                    '20%' => '20',
                    '30%' => '30',
                    '40%' => '40',
                    '50%' => '50',
                    '60%' => '60',
                    '70%' => '70',
                    '80%' => '80',
                    '90%' => '90',
                    '100%' => '100',
                ],
                'required'=> false,
                'attr'=> ['class' => 'select2 custom-select custom-select-lg mb-3 '],
            ])
            ->add('templateNbCol', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'required'=> false,
                'attr'=> ['class' => 'select2 custom-select custom-select-lg mb-3 '],
            ])
            ->add('templateImageFilter', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'choices' => [
                    'Format Bd' => 'bd',
                    'Format paysage' => 'paysage',
                ],
                'required'=> false,
                'attr'=> ['class' => 'select2 custom-select custom-select-lg mb-3 '],
            ])
            ->add('template2', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'class'=> Template::class,
                'query_builder' => function (TemplateRepository $er)  use ($options) {
                    return $er->getQbTemplate2();
                },
                'attr'=> ['class' => 'select2 custom-select custom-select-lg mb-3 ']
            ])
            ->add('posts', CollectionType::class, $options['posts']
//                [
//                'entry_type' => PostType::class,
//                'constraints' => $options['constraints'],
//                'prototype'=> true,
//                'prototype_name' => 'post',
//                'allow_add' => true,
//                'by_reference' => false,
//                'allow_delete' => true,
//                'label'=> false,
//                'entry_options' => ['label' => false, 'active'=> false, 'position'=>false, 'name'=>true, 'content'=>$options['content'], 'save_visibility' => false, 'save' => false, 'saveAndAdd' => false, 'saveAndAddPost' => false, 'saveAndAddSectionPost' => false,],
//            ]
    )
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
        $section->setTemplate2($form->getViewData()->getTemplate2());
        $event->setData($section);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
            'save_visibility' => true,
            'remote_pictures' => true,
            'content' => true,
            'menu' => false,
            'posts' =>                 [
                'entry_type' => PostType::class,
                'constraints' => new \Symfony\Component\Validator\Constraints\Valid(),
                'prototype'=> true,
                'prototype_name' => 'post',
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'label'=> false,
                'entry_options' => ['label' => false, 'active'=> false, 'position'=>false, 'name'=>true, 'content'=> true, 'save_visibility' => false, 'save' => false, 'saveAndAdd' => false, 'saveAndAddPost' => false, 'saveAndAddSectionPost' => false,],
            ],
            'full_template' => true,
            'name_constraints' => [],
            'position' => true,
            'saveAndAddSectionPost' => false,
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();
                $image_valid = true;
                if(is_null($data->getRemote())){
                      $image_valid = true;
                }else{
                    if(is_null($data->getRemote()->getUrl())) {
                        $image_valid = false;
                    }
                }
                if(!$image_valid){
                    foreach($data->getPosts() as $post){
                        if( !$post->getImageFile() && !$post->getFileName()){
                            $image_valid = false;
                            break;
                        }
                    }
                }
                if($data instanceof Section ){
                    $template = $data->getTemplate();
                }
                if($data instanceof Post){
                    $template = $data->getSection()->getTemplate();
                }
                if ('libre' == $template->getCode() || 'libre_code' == $template->getCode()) {
                    return ['Default','content'];
                }else{
                    if(!$image_valid){
                        return ['Default','image'];
                    }
                }
                return ['Default'];
            },
        ]);
    }
}


