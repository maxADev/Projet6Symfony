<?php

// src/Form/Type/ChangePasswordType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' =>  'Mot de passe',
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' =>  'Confirmation Mot de passe',
            ])
            ->add('save', SubmitType::class, ['label' => 'Valider'])
        ;
    }
}
