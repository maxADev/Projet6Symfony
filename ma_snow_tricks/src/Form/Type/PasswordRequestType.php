<?php

// src/Form/Type/PasswordRequestType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class PasswordRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' =>  'Email',
                'constraints' => [new NotBlank(['message' => 'Vous devez indiquer une adresse email']),
                                  new Email(['message' => 'Le format de l\'adresse email n\'est pas valide'])
                                 ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Valider'])
        ;
    }
}
