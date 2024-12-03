<?php

namespace App\Form\Admin\Libre;

use App\Entity\Hermes\Section;

use App\Entity\Hermes\Template;
use App\Repository\TemplateRepository;
use App\Form\Admin\AbstractNameBaseType;
use App\Form\Admin\Libre\PostLibreType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionLibreType extends AbstractNameBaseType
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
                    'query_builder' => function (TemplateRepository $er) use ($options) {
                        return $er->getQbTemplateLibre();
                    },
                    'attr' => ['class' => 'd-none ']
                ])
            ->add('posts', CollectionType::class, [
                'entry_type' => PostLibreType::class,
                'prototype'=> true,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'entry_options' => ['label' => false, 'name' => true, 'content' => true, 'url' => true, 'save' => false, 'saveAndAdd' => false, ],
            ])
        ;

        $builder->addEventListener(
            FormEvents::SUBMIT,
            [$this, 'onSubmitData']
        );
    }

    public function onSubmitData(FormEvent $event)
    {
        $section = $event->getData();
        $form = $event->getForm();
        $section->setName($form->getViewData()->getTemplate()->getName() . '-' . $section->getMenu()->getName());
        $section->setTemplate($form->getViewData()->getTemplate());
        $section->setTemplate2($form->getViewData()->getTemplate2());
        $event->setData($section);
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
            'saveLibre' => true,
        ]);
    }
}
