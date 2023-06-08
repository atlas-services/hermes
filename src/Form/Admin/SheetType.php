<?php

namespace App\Form\Admin;

use App\Entity\Hermes\Sheet;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SheetType extends AbstractNameBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['label_name'] = 'sheet.name';
        $options['tooltip'] = 'texte qui apparaitra sur l\'entête de menu';
        parent::buildForm($builder, $options);
        $builder
            ->add('locale', 'Symfony\Component\Form\Extension\Core\Type\LocaleType', [
                'choice_translation_locale' => 'fr',
                'required' => false,
                'label' => 'global.locale',
            ])
            ->add('referenceName', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'required' => true,
                'label' => 'global.name_reference',
            ]);
        if ($options['position']) {
            $builder
                ->add('position', 'Symfony\Component\Form\Extension\Core\Type\NumberType', [
                    'required' => false,
                    'empty_data' => (string)$options['position'],
                    'label' => 'global.position',
                ]);
        }
        if ($options['image_file']) {
            $builder
                ->add('imageFile', 'Vich\UploaderBundle\Form\Type\VichImageType', [
                    'required' => false,
                    'label' => 'global.image',
                    'translation_domain' => 'messages',
                    'download_uri' => false,
                ]);
        }

            $builder->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onPostSubmitData']
            );

    }

    public function onPostSubmitData(FormEvent $event)
    {
        $sheet = $event->getData();
        if (is_null($sheet->getId())) {
            $sheet->setActive(true);
            $sheet->setCode($this->slugify($sheet->getName()));
        }
        $sheet->setSlug($this->slugify($sheet->getName()));
        $event->setData($sheet);
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
            'data_class' => Sheet::class,
            'active' => true,
            'position' => true,
            'name' => true,
            'name_required' => false,
            'name_constraints'=> new NotBlank(['message'=> 'error_message.sheet.name']),
            'image_file' => true,
            'save' => true,
            'saveAndAdd' => true,
            'saveAndAddLabel' => 'sheet.update_next',
            'saveAndAddPost' => false,
            'saveAndAddSectionPost' => false,
            // 'saveAndAddHermesListe' => true,
        ]);
    }
}
