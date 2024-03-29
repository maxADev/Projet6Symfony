<?php

// src/Form/Type/CreateTrickType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use App\Entity\Trick;
use App\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\GroupRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\Type\TrickVideoType;

class TrickType extends AbstractType
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' =>  'Titre du trick',
                'constraints' => [new NotBlank(['message' => 'Vous devez renseigner un titre']),
                                  new Length([
                                    'min'=> 3,
                                    'max'=> 15,
                                    'minMessage'=> 'Le titre doit faire 3 caractères minimum',
                                    'maxMessage'=> 'Le titre doit faire 150 caractères maximum',
                                  ])],
            ])
            ->add('description', TextareaType::class, [
                'label' =>  'Description',
                'constraints' => [new NotBlank(['message' => 'Vous devez renseigner une description']),],
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'multiple' => true,
                'label' =>  'Image(s)',
                'required' => false,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpg',
                                    'image/jpeg',
                                ],
                                'mimeTypesMessage' => 'Le format n\'est pas valide',
                            ]),
                        ],
                    ]),
                ],
            ])
            ->add('trickVideos', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'label' => false,
                'entry_type' => TrickVideoType::class,
                'entry_options' => [
                    'attr' => ['class' => 'trick-video-item',],
                    'label' => false,
                ],
                'by_reference' => false
            ])
            ->add('trickGroup', EntityType::class, [
                'class' => Group::class,
                'constraints' => [new NotBlank(['message' => 'Vous devez renseigner un groupe']),],
            ])
            ->add('save', SubmitType::class, ['label' => 'Valider']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
