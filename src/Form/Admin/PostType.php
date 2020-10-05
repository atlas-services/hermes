<?php

namespace App\Form\Admin;

use App\Entity\Post;
use App\Entity\Tag;
use App\Form\Admin\TagType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractNameBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['label_name']= 'post.name';
        $options['tooltip']= 'Nom du post';
        parent::buildForm($builder, $options);
        if ($options['section']) {
            $builder
                ->add('section', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                    'required' => false,
                    'class' => 'App\Entity\Section',
                    'choice_label' => 'template',
                    'label_format' => 'section.template',
                    'attr'=> ['class' => 'custom-select custom-select-lg mb-3']
                ]);
        }
        if ($options['position']) {
            $builder
                ->add('position', 'Symfony\Component\Form\Extension\Core\Type\NumberType', [
                    'required' => false,
                    'label' => 'global.position',
                ]);
        }
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
            ->add('content', 'FOS\CKEditorBundle\Form\Type\CKEditorType', [
                'constraints' => new NotBlank(['message'=> 'error_message.post.content']),
                'config_name' => 'my_config',
//                'config' => array(
//                    'extraPlugins' => 'ckeditor-gwf-plugin',
//                ),
//                'plugins' => array(
//                    'ckeditor-gwf-plugin' => array(
//                        'path'     => '/bundles/fosckeditor/plugins/font/gwf/', // with trailing slash
//                        'filename' => 'plugin.js',
//                    ),
//                ),
                'label_format' => 'global.content',
                'required'=> true,
                'attr'=> ['id'=> 'app_cke_post','class' => 'mb-3 w-100']
            ]);
        }
        $builder->add('tags', CollectionType::class, [
            'entry_type' => TagType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'allow_delete' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'active' => true,
            'section' => false,
            'position' => true,
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
