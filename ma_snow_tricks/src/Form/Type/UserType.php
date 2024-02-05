<?php

// src/Form/Type/UserType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\EqualTo;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' =>  'Nom d\'utilisateur',
            ])
            ->add('email', TextType::class, [
                'label' =>  'Email',
            ])
            ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Le mot de passe et la confirmation du mot de passe sont différents',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmation du mot de passe'],
                    'constraints' => [new NotBlank(['message' => 'Le mot de passe ne peut être vide']),
                                      new Length([
                                        'min'=> 5,
                                        'max'=> 15,
                                        'minMessage'=> 'Le mot de passe doit faire 5 caractères minimum',
                                        'maxMessage'=> 'Le mot de passe doit faire 15 caractères maximum',
                                      ])],
            ])
            ->add('cgu', CheckboxType::class, [
                'label' =>  'Accepter les Conditions générales d\'utilisation',
            ])
            ->add('save', SubmitType::class, ['label' => 'Valider'])
        ;
    }
}
