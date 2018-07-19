<?php

namespace App\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => 'form.register.firstname',
                'attr' => [
                    'placeholder' => 'form.placeholder.firstname'
                ]
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => 'form.register.lastname',
                'attr' => [
                    'placeholder' => 'form.placeholder.lastname'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'form.register.email',
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => 'form.register.password',

            ])
            ->add('Enregistrer', SubmitType::class, array(
                'label' => 'form.register.btn',
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
//            'data_class' => Article::class
            'data_class' => UserRequest::class, #On ne prend plus une instance d'article, mais directement le service lié
            'translation_domain' => 'forms',

        ]);
    }
}
