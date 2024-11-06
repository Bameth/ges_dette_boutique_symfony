<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('surname', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm',
                    'placeholder' => 'Surname',
                ],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le surname ne doit pas être vide.']),
                    new Assert\Length(['max' => 50, 'maxMessage' => 'Le surname ne peut pas dépasser {{ limit }} caractères.']),
                ],
            ])
            ->add('telephone', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm',
                    'placeholder' => 'Téléphone',
                ],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le numéro de téléphone ne doit pas être vide.']),
                    new Assert\Length(['max' => 50, 'maxMessage' => 'Le numéro de téléphone ne peut pas dépasser {{ limit }} caractères.']),
                    new Assert\Regex(['pattern' => '/^\+?\d{9,12}$/', 'message' => 'Le numéro de téléphone doit être valide et contenir entre 9 et 12 chiffres.']),
                ],
            ])
            ->add('adresse', TextareaType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm',
                    'placeholder' => 'Adresse',
                ],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => "L'adresse ne doit pas être vide."]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
