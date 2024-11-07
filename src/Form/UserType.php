<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
                    new Assert\NotBlank([
                        'message' => "Le nom ne doit pas être vide.",
                        'groups' => ['WITH_COMPTE'],
                    ]),
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
                    new Assert\NotBlank([
                        'message' => "Le prénom ne doit pas être vide.",
                        'groups' => ['WITH_COMPTE'],
                    ]),
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
                    'autocomplete' => 'username',
                ],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => "Le login ne doit pas être vide.",
                        'groups' => ['WITH_COMPTE'],
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm',
                    'placeholder' => 'Mot de passe',
                    'autocomplete' => 'new-password',
                ],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => "Le mot de passe ne doit pas être vide.",
                        'groups' => ['WITH_COMPTE'],
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['WITH_COMPTE'],
        ]);
    }
}
