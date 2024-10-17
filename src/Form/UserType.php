<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                'label' => 'Nom',
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis.']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm',
                    'placeholder' => 'Prénom',
                ],
                'label' => 'Prénom',
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est requis.']),
                ],
            ])
            ->add('login', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm',
                    'placeholder' => 'Login',
                ],
                'label' => 'Login',
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Le login est requis.']),
                ],
            ])
            ->add('password', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm',
                    'placeholder' => 'Mot de passe',
                ],
                'label' => 'Mot de passe',
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe est requis.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
