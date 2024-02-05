<?php

// src/Form/Type/UserType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

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
            ->add('password', PasswordType::class, [
                'label' =>  'Mot de passe',
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' =>  'Confirmation Mot de passe',
            ])
            ->add('cgu', CheckboxType::class, [
                'label' =>  'Accepter les Conditions générales d\'utilisation',
            ])
            ->add('save', SubmitType::class, ['label' => 'Valider'])
        ;
    }
}
