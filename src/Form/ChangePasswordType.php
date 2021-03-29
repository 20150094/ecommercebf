<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
            'disabled'=>true,
            'label'=>'Votre email'])
            ->add('ancien_password',PasswordType::class,[
                'mapped'=>false,
                'label'=>'mot de passe',
                'attr'=>[
                    'placeholder'=>'veuillez saisir votre mot de passe actuel'
                ]
            ])

            ->add('nouveau_password',RepeatedType::class,
            ['type'=>PasswordType::class,
            'mapped'=>false,
            'invalid_message'=>'les mot de passe doivent être identique',
            'required'=>true,

            'first_options'=>['label'=>'mon nouveau mot de passe',
            'attr'=>['placeholder'=>'merci de saisir votre mot de passe']],
            'second_options'=>['label'=>'confirmer votre nouveau mot de passe',
            'attr'=>['placeholder'=>'merci de confirmer votre nouveau mot de passe']]
            ])




            ->add('firstName',TextType::class,[
            'disabled'=>true,
            'label'=>'Prenom'])
            ->add('lastName',TextType::class,[
            'disabled'=>true,
            'label'=>'Nom'])

            ->add('submit',SubmitType::class,
            ['label'=>"Modifier"]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
