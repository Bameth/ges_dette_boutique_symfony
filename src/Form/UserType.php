<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm',
                    'placeholder' => 'Nom',
                ],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => "Le nom ne doit pas être vide."]),
                    new Assert\Length([
                        'max' => 50,
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm',
                    'placeholder' => 'Prénom',
                ],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => "Le prénom ne doit pas être vide."]),
                    new Assert\Length([
                        'max' => 50,
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('login', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm',
                    'placeholder' => 'Login',
                ],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => "Le login ne doit pas être vide."]),
                    new Assert\Length([
                        'max' => 100,
                        'maxMessage' => 'Le login ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm',
                    'placeholder' => 'Mot de passe',
                ],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => "Le mot de passe ne doit pas être vide."]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ]);
            // ->add('password', PasswordType::class, [
            //         'attr' => [
            //             'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm',
            //             'placeholder' => 'Mot de passe',
            //         ],
            //         'label' => false,
            //         'constraints' => [
            //             new Assert\NotBlank(['message' => "Le mot de passe ne doit pas être vide."]),
            //             new Assert\Length([
            //                 'max' => 255,
            //                 'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.',
            //             ]),
            //             new Assert\Regex([
            //                 'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
            //                 'message' => 'Le mot de passe doit contenir au moins 8 caractères, avec au moins une lettre et un chiffre.',
            //             ]),
            //         ],
            //     ]); 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

