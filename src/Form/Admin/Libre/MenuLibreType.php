<?php

namespace App\Form\Admin\Libre;

use App\Entity\Hermes\Menu;

use App\Entity\Hermes\Section;
use App\Entity\Hermes\Sheet;
use App\Repository\SheetRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MenuLibreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['label_name']= 'menu.name';
        $options['tooltip']= 'texte qui apparaitra sur le menu';
        $options['name_required'] = true;
        $options['active'] = true;
        $options['name'] = true;
        parent::buildForm($builder, $options);
        if($options['sheet']){
        $builder
            ->add('sheet', 'Symfony\Bridge\Doctrine\Form\Type\EntityType',
            [
                'class'=> Sheet::class,
                'required' => true,
                'label' => 'global.sheet',
                'query_builder'=>  function (SheetRepository $er) {
                    return $er->getQbSheets();
                },
                'attr'=> ['class' => 'select2 custom-select custom-select-lg mb-3 ']
            ]);
        }
        $builder
            ->add('position','Symfony\Component\Form\Extension\Core\Type\NumberType', [
                'required' => false,
                'label' => 'global.position',
            ])
             ->add('imageFile', 'Vich\UploaderBundle\Form\Type\VichImageType',[
                'required' => false,
                'label' => 'global.image',
                'translation_domain'=> 'messages',
                'download_uri'=> false,
            ])
            ->add('sections', CollectionType::class, [
                'required' => false,
                'entry_type' => SectionLibreType::class,
                'prototype'=> true,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'label'=> false,
                'entry_options' => ['label' => false],
            ])
            ->add('save', SubmitType::class, [
                'icon_before' => '<i class="fa fa-save"></i>',
                'label_html' => true,
                'label' => 'global.update'
            ])
            ->add('saveLibre', SubmitType::class, [
            'icon_before' => '<i class="fa fa-save"></i> <i class="fa fa-plus-circle"></i>',
            'label_html' => true,
            'label' =>  'global.new_post_libre'
        ]);
        ;

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            [$this, 'onPostSubmitData']
        );
    }

    public function onPostSubmitData(FormEvent $event)
    {
        $menu= $event->getData();
        $sections = $menu->getSections();
        if(is_null($menu->getId())){
            $menu->setActive(true);
            $menu->setCode($this->slugify($menu->getName()));
            $menu->setSlug($this->slugify($menu->getName()));
            $sections = $menu->getSections();
            $section = $sections[0];

            if(!is_null($section)){
                $section->setName($menu->getName().'-'.$section->getName());
            }
            $event->setData($menu);
        }
    }

    protected function slugify($string){
        $sans_accent = $this->enleveaccents( $string );
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $sans_accent ), '-'));
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
            'label_name' =>'global.name',
            'name_constraints'=> new NotBlank(['message'=> 'error_message.menu.name']),
            'sheet' => true,
            'save' => true,
            'saveAndAdd' => true,
            'saveAndAddLabel' => 'menu.update_next',
            'saveAndAddPost' => true,
            'saveAndAddSectionPost' => true,
            'saveLibre' => true,
        ]);
    }
}


