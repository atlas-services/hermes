<?php

namespace App\Form;

use App\Entity\Hermes\Contact;
use App\Entity\Hermes\Temoignage;
use App\Entity\Hermes\User;
use App\Entity\Interfaces\ContactInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public $emailTo;
    protected $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->emailTo = $options['emailTo'];
        $contact_bgcolor_subject = $options['contact_bgcolor_subject'];
        $contact_color_subject = $options['contact_color_subject'];
        $contact_bgcolor_input = $options['contact_bgcolor_input'];
        $contact_color_input = $options['contact_color_input'];

        $contact_subjects = $options['contact_subjects'];
        $builder
            ->add('subject', ChoiceType::class, [
                'validation_groups' => false,
                'choices' => $contact_subjects,
                'empty_data' => function () use ($options): string {
                        return ucfirst($options['validation_groups'][0]);
                    },
                'attr' => ['class' => "form-select border-light rounded-0 text-center fst-italic", 'style' => "color:$contact_color_subject; background-color:$contact_bgcolor_subject" ,'id' => "subject", ]
                ]
            )
            ->add('firstname', TextType::class,
                [
                    'validation_groups' => ['contact'],
                    'constraints' => new Assert\Type('string'),
                    'label' => 'Prénom', 
                    'attr' => ['class' => "border-light rounded-0 ", 'style' => "color:$contact_color_input; background-color:$contact_bgcolor_input" ,'id' => "name", 'data-validation-required-message' => "Merci de saisir votre nom.",
                    'placeholder' => "formulaire.firstname_placeholder"]
                ]
            )
            ->add('lastname', TextType::class,
                [
                    'validation_groups' => ['contact'],
                    'constraints' => new Assert\Type('string'),
                    'label' => 'Nom', 
                    'attr' => ['class' => "border-light rounded-0", 'style' => "color:$contact_color_input; background-color:$contact_bgcolor_input" ,'id' => "name", 'data-validation-required-message' => "Merci de saisir votre nom.",
                    'placeholder' => "formulaire.lastname_placeholder"]
                ]
            )
            ->add('email', EmailType::class,
                [
                    'validation_groups' => ['contact', 'newsletter', 'livredor'],
                    'label' => '* Email ', 
                    'attr' => ['class' => "border-light rounded-0", 'style' => "color:$contact_color_input; background-color:$contact_bgcolor_input" , 'id' => "name", 'data-validation-required-message' => "Merci de saisir votre émail.",
                    'placeholder' => "formulaire.email_placeholder"]
                ]
            )
            ->add('telephone', TelType::class,
                [
                    'validation_groups' => ['contact'],
                    'constraints' => new Assert\Length(['min' => 10, 'max' => 10, 'exactMessage' => 'contact.message.telephone']),
                    'label' => 'Téléphone ', 'required' => false, 
                    'attr' => ['class' => "border-light rounded-0 ", 'style' => "color:$contact_color_input; background-color:$contact_bgcolor_input" , 'id' => "phone",
                    'data-validation-required-message' => "error_phone.", 'placeholder' => "formulaire.phone_placehoder"]
                ]
            )
            ->add('content', TextareaType::class,
                [
                    'validation_groups' => ['contact', 'livredor'],
                    'label' => '* Votre message ', 
                    'attr' => ['class' => "border-light rounded-0 ", 'style' => "color:$contact_color_input; background-color:$contact_bgcolor_input" ,'id' => "message", 'data-validation-required-message' => "Please enter your name.",
                    'placeholder' => "formulaire.message_placeholder", 'rows' => '15']
                ]
            )
            ->add('subscribeNewsletterButton', SubmitType::class, 
                [
                    'label' => 'formulaire.subscribe',
                    'attr' => ['class' => $options['bgcolor_btn'] . " h-rounded-lg-4 btn-xl text-center mt-2 mt-sm-0 py-auto"],
                ])
                ;

            $builder->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onPostSubmitData']
            );
        }

        // /**
        //  * @param array<string,mixed> $options
        //  */
        // public function buildView(FormView $view, FormInterface $form, array $options)
        // {
        //     $attr = ['novalidate' => 'novalidate'] ;
        //     $view->vars['attr'] = array_merge($view->vars['attr'], $attr);
        // } 
        
    public function onPostSubmitData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $form->getData();
        $subject = $data->getSubject();

        // Newsletter
        if ( strtolower(ContactInterface::NEWSLETTER) == strtolower($subject) || $form->get('subscribeNewsletterButton')->isClicked()){
            $email = $data->getEmail();
            $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $email]); 
            if (is_null($user)){
                $user = new User();
                $firstname = $data->getFirstName();
                if(is_null($firstname)){
                    $firstname = 'newsletter';
                }
                $user->setFirstname($firstname);
                $lastname = $data->getLastName();
                if(is_null($lastname)){
                    $lastname = 'newsletter';
                }
                $user->setLastname($lastname);
                $user->setEmail($email);
                $user->setRoles(['ROLE_NEWSLETTER']);
            }else{
                $user->setActiveNewsletter(true);
                $user->setRoles(['ROLE_NEWSLETTER']);
            }
            $user->setPassword('NEWSLETTER');
            $this->doctrine->getManager()->persist($user);
            $this->doctrine->getManager()->flush();              
        }

        // Livre d'or
        if (strtolower(ContactInterface::LIVREDOR) == strtolower($subject) ){
            $temoignage = new Temoignage();
            $temoignage->setActive(false);
            $temoignage->setFirstName($data->getFirstName());
            $temoignage->setLastName($data->getLastName());
            $temoignage->setEmail($data->getEmail());
            $temoignage->setContent($data->getContent());
            $this->doctrine->getManager()->persist($temoignage);
            $this->doctrine->getManager()->flush();              
        }

    }

    public function getName()
    {
        return 'contact';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'emailTo' => null,
            'bgcolor_btn' => 'btn-outline-danger',
            'contact_bgcolor_subject' => '#000000',
            'contact_color_subject' => '#FFFFFF',
            'contact_bgcolor_input' => '#FFFFFF',
            'contact_color_input' => '#000000',
            'contact_subjects' => [ Contact::CONTACT => Contact::CONTACT],
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => 'csrf_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'contact_item',
        ]);
    }
}
