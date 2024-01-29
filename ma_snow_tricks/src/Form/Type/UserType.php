<?php

// src/Form/Type/UserType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' =>  'Nom',
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
            ->add('image', FileType::class, [
                'label' =>  'Image',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Le format n\'est pas valide',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Valider'])
        ;
    }
}
