<?php

namespace App\Form\Admin\Libre;

use App\Entity\Hermes\Post;
use App\Form\Traits\ImageFileTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType as AppEditorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostLibreType extends AbstractType
{
    use ImageFileTrait;
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['label_name']= 'post.name';
        $options['tooltip']= 'Nom du post';
        parent::buildForm($builder, $options);
        $builder
            ->add('name', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'constraints' => $options['name_constraints'],
            'required' => false, //$options['name_required'],
            'label' => $options['label_name'],
            'attr'=> ['class' => 'mb-3'],
            'label_attr'=> [
                'data-bs-toggle' => 'tooltip',
                'data-placement'=> 'left',
                'data-html' => 'true',
    //                        'title'=> $options['title']
            ]
        ]);
        if ($options['image_file']) {
            $builder
                ->add('imageFile', 'Vich\UploaderBundle\Form\Type\VichImageType', $this->getImafileOptions());
        }
        if ($options['content']) {
        $builder
            ->add('content', AppEditorType::class, [
                'label_format' => 'global.content',
                'required'=> true,
                'attr'=> ['id'=> 'post_content', 'hidden' => false, 'class' => 'mb-3 w-100']
            ]);
        }
        $builder
            ->add('save', SubmitType::class, [
                'icon_before' => '<i class="fa fa-save"></i>',
                'label_html' => true,
                'label' => 'global.update'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'active' => true,
            'section' => false,
            'url' => true,
            'name' => true,
            'name_constraints'=> new NotBlank(['message'=> 'error_message.post.name']),
            'content' => true,
            'image_file' => true,
            'save' => true,
            'saveAndAdd' => true,
            'saveAndAddLabel' => 'menu.update_next',
            'saveLibre' => false,
        ]);
    }
}
