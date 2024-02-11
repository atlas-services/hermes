<?php

namespace App\Form\Admin;

use App\Entity\Hermes\BlockPost;
use Symfony\Component\Form\Extension\Core\Type\TextareaType as AppEditorType ;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BlockPostType extends AbstractNameBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['label_name']= 'post.name';
        parent::buildForm($builder, $options);

        if ($options['block']) {
            $builder
                ->add('block', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                    'required' => false,
                    'class' => 'App\Entity\Hermes\Block',
                    'choice_label' => 'name',
                    'label_format' => 'block',
                    'attr' => ['class' => 'select2 custom-select select2 custom-select-lg mb-3']
                ]);
        }
        $builder->add('name');
        $builder->add('position', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
            'required' => false,
            'attr' => [
                'min' => 0,
                'max' => 99,                       
                'class' => 'custom-select custom-select-lg mb-3 ',
                'label' => 'global.position',
            ],
            'choices' => range(0, 99),
        ]);;
        if ($options['image_file']) {
            $builder
                ->add('imageFile', 'Vich\UploaderBundle\Form\Type\VichImageType', [
                    'required' => false,
                    'label' => 'global.image',
                    'translation_domain'=> 'messages',
                    'download_uri' => false,
                ]);
        }
        if ($options['content']) {
        $builder
            ->add('content', AppEditorType::class, [
                'label_format' => 'global.content',
                'required'=> true,
                'attr'=> ['id'=> 'post_content', 'hidden' => false,'class' => 'mb-3 w-100']
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BlockPost::class,
            'active' => true,
            'block' => true,
            'name' => true,
            'name_required'=> false,
            'name_constraints'=> new NotBlank(['message'=> 'error_message.post.name']),
            'content' => true,
            'image_file' => true,
            'save_visibility' => true,
            'save' => true,
            'saveAndAdd' => true,
            'saveAndAddLabel' => 'menu.update_next',
            'saveAndAddPost' => true,
            'saveAndAddSectionPost' => false,
        ]);
    }
}
