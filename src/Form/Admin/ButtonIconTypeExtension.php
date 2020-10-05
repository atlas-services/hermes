<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class ButtonIconTypeExtension extends AbstractTypeExtension {
    /**
     * Return the class of the type being extended.
     */
    public static function getExtendedTypes(): iterable {
        // return FormType::class to modify (nearly) every field in the system
        return [ ButtonType::class, SubmitType::class ];
    }

    public function configureOptions( OptionsResolver $resolver ) {
        // makes it legal for ButtonType fields to have an this properties option
        $resolver->setDefined( [ 'icon_before', 'icon_after', 'icon' ] );
    }

    public function buildView( FormView $view, FormInterface $form, array $options ) {
        if ( isset( $options['icon'] ) ) {
            $view->vars['icon'] = $options['icon'];
        }
        if ( isset( $options['icon_before'] ) ) {
            $view->vars['icon_before'] = $options['icon_before'];
        }
        if ( isset( $options['icon_after'] ) ) {
            $view->vars['icon_after'] = $options['icon_after'];
        }
    }
}