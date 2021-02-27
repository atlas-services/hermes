<?php

namespace App\Form\Admin;

use App\Entity\Hermes\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                'label' => 'user.firstname',
            ])
            ->add('lastname', null, [
                'label' => 'user.lastname',
            ])
            ->add('email', null, [
                'label' => 'user.email',
            ])
            ->add('currency', 'Symfony\Component\Form\Extension\Core\Type\CurrencyType',[
                'label' => 'user.devise',
                'attr'=> ['class' => 'select2 custom-select select2 custom-select-lg mb-3']
            ])
            ->add('password', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', [
                'label' => 'user.password',
            ]);

        if(!in_array('ROLE_CUSTOMER', $builder->getData()->getRoles())){
            $builder
                ->add('roles', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                    [
                        'multiple' => true,
                        'expanded' => true,
                        'choices' => $options['roles'],
                        'disabled' => $options['disable_roles']
                    ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'disable_roles' => false,
            'roles' => ['ROLE_USER'],
        ]);
    }
}
