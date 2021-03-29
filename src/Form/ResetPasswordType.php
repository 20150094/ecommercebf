<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
        ->add('nouveau_password',RepeatedType::class,
        ['type'=>PasswordType::class,
        
        'invalid_message'=>'les mot de passe doivent être identique',
        'required'=>true,

        'first_options'=>['label'=>'mon nouveau mot de passe',
        'attr'=>['placeholder'=>'merci de saisir votre mot de passe']],
        'second_options'=>['label'=>'confirmer votre nouveau mot de passe',
        'attr'=>['placeholder'=>'merci de confirmer votre nouveau mot de passe']]
        ])


        ->add('submit',SubmitType::class,
        ['label'=>"Mettre à jour mon mot de passe",
        'attr'=>[
            'class'=>'btn-block btn-info'
        ]]
    );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
