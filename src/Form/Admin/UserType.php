<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('password', 'Symfony\Component\Form\Extension\Core\Type\PasswordType')
            ->add('roles', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                [
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => User::ROLES_AVAILABLES,
                    'disabled' => $options['disable_roles']
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'disable_roles' => true,
        ]);
    }
}
