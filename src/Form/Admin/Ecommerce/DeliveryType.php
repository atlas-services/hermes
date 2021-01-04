<?php

namespace App\Form\Admin\Ecommerce;

use App\Entity\Address;
use App\Entity\Delivery;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $user;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Delivery|null $article */
        $delivery = $options['data'] ?? null;
        $isEdit = $delivery && $delivery->getId();
        $this->user = $options['user'];

        $builder
            ->add('name', null, [
                'label' => 'order.delivery',
            ])
            ->add('deliveryMethod', ChoiceType::class,
                [
                    'label' =>   'order.delivery_method',
                    'placeholder' => 'order.placeholder_delivery_method',
                    'choices' =>   Delivery::DELIVERY_CHOICES,
                    'attr' => ['class' => 'select-delivery-method select2 custom-select select2 custom-select-lg mb-3']

                ])
            ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Liraison|null $data */
                $data = $event->getData();
                if (!$data) {
                    return;
                }
                $this->setupAddressField(
                    $event->getForm(),
                    $data->getDeliveryMethod()
                );
            }
        );

        $builder->get('deliveryMethod')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $this->setupAddressField(
                    $form->getParent(),
                    $form->getData()
                );
            }
        );

    }

    private function setupAddressField(FormInterface $form, ?string $deliveryMethod)
    {
        if (null === $deliveryMethod) {
            $form->remove('address');
            return;
        }
        $choices = $this->getDeliveryMethodNameChoices($deliveryMethod);
        if (null === $choices) {
            $form->remove('address');
            return;
        }
        $form->add('address', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
            'placeholder' => 'order.placeholder_delivery_address',
            'choices' => $choices,
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Delivery::class,
            'user'=> null,
        ]);
    }

    private function getDeliveryMethodNameChoices(string $deliveryMethod)
    {
        $CC =  ['Adresse1 CC' => 'Adresse1 CC'];
        $RE =  ['Adresse relais non prise en compte actuellement' => ''];

            switch ($deliveryMethod) {
                case Delivery::DELIVERY_CC:
                    $deliveryMethodNameChoices = $CC;
                    break;
                case Delivery::DELIVERY_RELAY:
                    $deliveryMethodNameChoices = $RE;
                    break;
                case Delivery::DELIVERY_HOME:
                case Delivery::DELIVERY_EXPRESS:
                    $addresses = $this->entityManager->getRepository(Address::class)->findByUser($this->user);
                    $deliveryMethodNameChoices = $this->getAddressesChoices($addresses);
                    break;
            }

            return $deliveryMethodNameChoices;

    }

    private function getAddressesChoices($addresses)
    {
        $choices = [] ;
        foreach($addresses as $address){
            $choices[$address->__toString()] = $address->__toString();
        }
        return $choices;

    }
}
