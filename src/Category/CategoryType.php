<?php

namespace App\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) :void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom de la catégorie',
                'attr' => [
                    'placeholder' => 'Nom de la catégorie'
                ]
            ])
            ->add('Enregistrer', SubmitType::class, array(
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ));
    }

    /**
     * Définir les options par défaut pour le formulaire
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            #Indiquer quel type de donnée sera a traiter par un formulaire
            'data_class' => CategoryRequest::class #On ne prend plus une instance d'article, mais directement le service lié

        ]);
    }
}
