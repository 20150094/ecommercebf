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
use libphonenumber\PhoneNumberFormat;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,
            ['label'=>'email',
            'attr'=>['placeholder'=>'merci de saisir votre email']
            ])

            ->add('telephone',TelType::class,
            ['label'=>'telephone',
            
            'attr'=>['placeholder'=>'merci de saisir votre numéro de téléphone au format international +237678968545']
            ])
            ->add('password',RepeatedType::class,
            ['type'=>PasswordType::class,
            'invalid_message'=>'les mot de passe doivent être identique',
            'required'=>true,

            'first_options'=>['label'=>'mot de passe',
            'attr'=>['placeholder'=>'merci de saisir votre mot de passe']],
            'second_options'=>['label'=>'confirmer votre mot de passe',
            'attr'=>['placeholder'=>'merci de confirmer votre mot de passe']]
            ])

            

            ->add('firstName',TextType::class,
            ['label'=>'prenom',
            'attr'=>['placeholder'=>'merci de saisir votre prenom']
            ])
            ->add('lastName',TextType::class,
            ['label'=>'nom',
            'attr'=>['placeholder'=>'merci de saisir votre nom']
            ])
            ->add('submit',SubmitType::class,
            ['label'=>"s'inscrire",
                'attr'=>[
                    'class'=>'btn btn-lg btn-info btn-block mt-3'
                ]]
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
