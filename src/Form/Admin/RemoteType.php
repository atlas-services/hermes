<?php

namespace App\Form\Admin;

use App\Entity\Hermes\Remote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active')
            ->add('name')
            ->add('url', 'Symfony\Component\Form\Extension\Core\Type\UrlType', ['required' => false])
            ->add('directory', null, ['required' => false])
            ->add('username')
            ->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Remote::class,
        ]);
    }
}
