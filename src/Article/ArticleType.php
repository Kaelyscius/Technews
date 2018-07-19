<?php

namespace App\Article;

use App\Entity\Category;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    private $image_url;
    private $slug;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->image_url = $options['image_url'];
        $this->slug = $options['slug'];

        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Titre de l\'Article',
                'attr' => [
                    'placeholder' => 'Titre de l\'Article...'
                ]
            ]);
        if ($this->slug) {
            $builder
                ->add('slug', TextType::class, [
                    'required' => true,
                    'label' => false,
                    'attr' => [
                        'placeholder' => "Slug de l'Article"
                    ]
                ]);
        }
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => true,
                'label' => 'Catégorie',
            ])
            ->add('content', CKEditorType::class, [
                'required' => true,
                'label' => 'Titre de l\'Article',
                'attr' => [
                    'class' => 'ckeditor',
                    'placeholder' => 'Contenu de l\'article'
                ]
            ])
            ->add('featuredImage', FileType::class, [
                'required' => true,
                'label' => 'Image de l\'article',
                'data_class' => null,
                'attr' => [
                    'class' => 'dropify',
                    'data-default-file' => $this->image_url
                ]
            ])
            ->add('special', CheckboxType::class, [
                'label' => 'Article en spécial',
                'required' => false,

            ])
            ->add('spotlight', CheckboxType::class, [
                'label' => 'Article en spotlight',
                'required' => false,

            ])
            ->add('Enregistrer', SubmitType::class, array(
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ));
    }

    /**
     * Définir les options par défaut pour le formulaire
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            #Indiquer quel type de donnée sera a traiter par un formulaire
//            'data_class' => Article::class
            'data_class' => ArticleRequest::class,
            #On ne prend plus une instance d'article, mais directement le service lié
            'image_url' => null,
            'slug' => null
        ]);
    }
}
