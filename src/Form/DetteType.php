<?php

namespace App\Form;

use App\Entity\Dette;
use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out',
                    'placeholder' => 'Montant',
                ],
                'label' => 'Montant',
                'required' => false, // Définir comme non requis
            ])
            ->add('montantVerser', TextType::class, [
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out',
                    'placeholder' => 'Montant à Verser',
                ],
                'label' => 'Montant à Verser',
                'required' => false, // Définir comme non requis
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'surname',
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out',
                ],
                'label' => 'Client',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dette::class,
        ]);
    }
}
