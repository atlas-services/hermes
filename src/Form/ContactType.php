<?php

namespace App\Form;

use App\Entity\Hermes\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public $emailTo;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->emailTo = $options['emailTo'];
        $builder
            ->add('subject', ChoiceType::class, [
                'validation_groups' => false,
                'choices' => [
                    Contact::CONTACT => Contact::CONTACT ,
                    Contact::NEWSLETTER => Contact::NEWSLETTER ,
                    Contact::LIVREDOR => Contact::LIVREDOR ,
                ],
                'empty_data' => function () use ($options): string {
                        return ucfirst($options['validation_groups'][0]);
                    }
                ]
            )
            ->add('name', TextType::class,
                [
                    'validation_groups' => ['contact'],
                    'constraints' => new Assert\Type('string'),
                    'label' => 'Nom', 
                    'attr' => ['class' => "form-control", 'id' => "name", 'data-validation-required-message' => "Merci de saisir votre nom.",
                    'placeholder' => "formulaire.name_placeholder"]
                ]
            )
            ->add('email', EmailType::class,
                [
                    'validation_groups' => ['contact', 'newsletter', 'livredor'],
                    'label' => '* Email ', 
                    'attr' => ['class' => "form-control", 'id' => "name", 'data-validation-required-message' => "Merci de saisir votre émail.",
                    'placeholder' => "formulaire.email_placeholder"]
                ]
            )
            ->add('telephone', TextType::class,
                [
                    'validation_groups' => ['contact'],
                    'constraints' => new Assert\Length(['min' => 10, 'max' => 10, 'exactMessage' => 'contact.message.telephone']),
                    'label' => 'Téléphone ', 'required' => false, 
                    'attr' => ['class' => "form-control", 'id' => "phone",
                    'data-validation-required-message' => "error_phone.", 'placeholder' => "formulaire.phone_placehoder"]
                ]
            )
            ->add('message', TextareaType::class,
                [
                    'validation_groups' => ['contact', 'livredor'],
                    'label' => '* Votre message ', 
                    'attr' => ['class' => "form-control", 'id' => "message", 'data-validation-required-message' => "Please enter your name.",
                    'placeholder' => "formulaire.message_placeholder", 'rows' => '15']
                ]
            );
    }

    public function getName()
    {
        return 'contact';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'emailTo' => null,
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => 'csrf_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'contact_item',
        ]);
    }
}
