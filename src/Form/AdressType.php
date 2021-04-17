<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'nom de votre adresse',
                'attr'=>[
                    'placeholder'=>'nommez votre adresse'
                ]
            ])
            ->add('firstName',TextType::class,[
                'label'=>'votre prenom',
                'attr'=>[
                    'placeholder'=>' entrer votre prenom'
                ]
            ])
            ->add('lastName',TextType::class,[
                'label'=>'votre nom',
                'attr'=>[
                    'placeholder'=>' entrer votre nom'
                ]
            ])
            ->add('company',TextType::class,[
                'label'=>'votre societe',
                'required' => false,
                'attr'=>[
                    'placeholder'=>'facultatif(entrer le nom de votre société)'
                ]
            ])
            ->add('address',TextType::class,[
                'label'=>' votre adresse',
                'attr'=>[
                    'placeholder'=>' .............'
                ]
            ])
            ->add('postal',TextType::class,[
                'label'=>' votre code postal',
                'attr'=>[
                    'placeholder'=>' facultatif ( entrer votre code postal)'
                ]
            ])
            ->add('city',TextType::class,[
                'label'=>'votre ville',
                'required' => false,
                'attr'=>[
                    'placeholder'=>'entrer votre ville'
                ]
            ])
            ->add('pays',CountryType::class,[
                'label'=>' votre pays',
                'attr'=>[
                    'placeholder'=>'entrer votre pays'
                ]
            ])
            ->add('phone',telType::class,[
                'label'=>' votre téléphone',
                'attr'=>[
                    'placeholder'=>'entrer votre téléphone'
                ]
            ])
            ->add('Submit',SubmitType::class,[
                'label'=>'Valider',
                'attr'=>[
                    'class'=>'btn-info btn-block '
                ]
            ])

        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
