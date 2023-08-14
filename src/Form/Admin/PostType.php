<?php

namespace App\Form\Admin;

use App\Entity\Hermes\Post;
use App\Form\Traits\ImageFileTrait;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractNameBaseType
{
    use ImageFileTrait;
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['label_name']= 'post.name';
        $options['tooltip']= 'Nom du post';
        parent::buildForm($builder, $options);
        if ($options['section']) {
            $builder
                ->add('section', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                    'required' => false,
                    'class' => 'App\Entity\Hermes\Section',
                    'choice_label' => 'template',
                    'label_format' => 'section.template',
                    'attr'=> ['class' => 'select2 custom-select select2 custom-select-lg mb-3']
                ]);
        }
        if ($options['position']) {
            $builder
                ->add('position', 'Symfony\Component\Form\Extension\Core\Type\NumberType', [
                    'required' => false,
                    'label' => 'global.position',
                ]);
        }
        if ($options['url']) {
            $builder
                ->add('url', 'Symfony\Component\Form\Extension\Core\Type\UrlType', [
                    'required' => false,
                    'label' => 'global.url',
                ]);
        }
        if ($options['image_file']) {
            $builder
                ->add('imageFile', 'Vich\UploaderBundle\Form\Type\VichImageType', $this->getImafileOptions());
        }
        if ($options['content']) {
        $builder
            ->add('content', 'FOS\CKEditorBundle\Form\Type\CKEditorType', [
                'config_name' => 'my_config',
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
        if ($options['startPublishedAt']) {
            $builder->add('startPublishedAt', DateType::class, [
                'required' => false,
                'label_format' => 'global.startPublishedAt',
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ]);
        }
        if ($options['endPublishedAt']) {
            $builder->add('endPublishedAt', DateType::class, [
                'required' => false,
                'label_format' => 'global.endPublishedAt',
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'active' => true,
            'section' => false,
            'position' => true,
            'url' => true,
            'name' => true,
            'startPublishedAt' => true,
            'endPublishedAt' => true,
            'name_required'=> false,
            'name_constraints'=> new NotBlank(['message'=> 'error_message.post.name']),
            'content' => true,
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();
                $template = $data->getSection()->getTemplate();
                if ('libre' == $template->getType()) {
                    return ['Default','content'];
                }else{
                    if( !$data->getImageFile() && !$data->getFileName()){
                        return ['Default', 'image'];
                    }
                }
                return ['Default'];
            },
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
