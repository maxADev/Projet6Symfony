<?php

// src/Form/Type/UserType.php
namespace App\Form\Type;

use App\Entity\TrickVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Url;

class TrickVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('link', UrlType::class, [
                'label' => "Lien",
                'required'   => false,
                'constraints' => [new NotBlank(['message' => 'Le lien ne peut être vide']),
                                  new Url(['message' => 'Vous devez renseigner une url']),],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickVideo::class,
        ]);
    }
}
