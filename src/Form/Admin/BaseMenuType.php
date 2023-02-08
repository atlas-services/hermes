<?php

namespace App\Form\Admin;

use App\Entity\Hermes\Menu;

use App\Entity\Hermes\Section;
use App\Entity\Hermes\Sheet;
use App\Repository\SheetRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BaseMenuType extends AbstractNameBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['label_name'] = 'menu.name';
        $options['tooltip'] = 'texte qui apparaitra sur le menu';
        $options['name_required'] = true;
        $options['active'] = true;
        $options['name'] = true;
        parent::buildForm($builder, $options);
        $builder
            ->add('sheet', 'Symfony\Bridge\Doctrine\Form\Type\EntityType',
                [
                    'class' => Sheet::class,
                    'query_builder'=>  function (SheetRepository $er) {
                        return $er->getQbSheetsWithoutContact();
                    },
                    'choice_label' => function(Sheet $sheet) {
                        return sprintf('%s - %s', $sheet->getLocale(), $sheet->getName());
                    },
                    'required' => true,
                    'label' => 'global.sheet',
                    'attr' => ['class' => 'select2 custom-select custom-select-lg mb-3']
                ])
            ->add('position', 'Symfony\Component\Form\Extension\Core\Type\NumberType', [
                'required' => false,
                'label' => 'global.position',
            ])
            ->add('locale', 'Symfony\Component\Form\Extension\Core\Type\LocaleType', [
                'choice_translation_locale' => 'fr',
                'required' => false,
                'label' => 'global.locale',
            ])
            ->add('referenceName', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'required' => true,
                'label' => 'global.name_menu_reference',
            ])
            ->add('imageFile', 'Vich\UploaderBundle\Form\Type\VichImageType', [
                'required' => false,
                'label' => 'global.image',
                'translation_domain' => 'messages',
                'download_uri' => false,
            ]);

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            [$this, 'onPostSubmitData']
        );
    }

    public function onPostSubmitData(FormEvent $event)
    {
        $menu = $event->getData();
        $menu->setSlug($this->slugify($menu->getName()));
        $event->setData($menu);
    }

    protected function slugify($string)
    {
        $sans_accent = $this->enleveaccents($string);
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $sans_accent), '-'));
        return $slug;
    }

    protected function enleveaccents($chaine)
    {
        $str = htmlentities($chaine, ENT_NOQUOTES, 'utf-8');
        $str = preg_replace('#&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
        $str = preg_replace('#&[^;]+;#', '', $str);
        return $str;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
            'label_name' => 'global.name',
            'name_constraints'=> new NotBlank(['message'=> 'error_message.menu.name']),
            'save' => true,
            'saveAndAdd' => true,
            'saveAndAddLabel' => 'menu.update_next',
            'saveAndAddPost' => false,
            'saveAndAddSectionPost' => false,
        ]);
    }
}


