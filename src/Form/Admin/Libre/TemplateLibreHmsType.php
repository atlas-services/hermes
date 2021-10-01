<?php

namespace App\Form\Admin\Libre;

use App\Entity\Hermes\Section;

use App\Entity\Hermes\Template;
use App\Repository\TemplateRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateLibreHmsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', EntityType::class,
                [
                    'class'=> Template::class,
                    'query_builder' => function (TemplateRepository $er) use ($options) {
                        return $er->getQbTemplateLibreHms();
                    },
                ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Template::class,
        ]);
    }
}


