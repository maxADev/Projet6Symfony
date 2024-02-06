<?php

// src/Form/Type/ChangePasswordType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('save', SubmitType::class, ['label' => 'Valider'])
        ;
    }
}
