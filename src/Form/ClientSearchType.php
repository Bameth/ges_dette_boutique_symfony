<?php

namespace App\Form;

use App\Model\ClientSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('telephone', TextType::class, [
                'required' => false,
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Entrez le numéro de téléphone',
                    'class' => 'border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full mb-4' // Utiliser mb-4 pour l'espace en bas
                ],
            ])
            ->add('surname', TextType::class, [
                'required' => false,
                'label' => 'Surname',
                'attr' => [
                    'placeholder' => 'Entrez le surname',
                    'class' => 'border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full mb-4' // Utiliser mb-4 pour l'espace en bas
                ],
            ]);
            // Uncomment if you want to add the 'compte' checkbox
            // ->add('compte', CheckboxType::class, [
            //     'required' => false,
            //     'label' => 'A un compte',
            //     'attr' => [
            //         'class' => 'ml-2'
            //     ],
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientSearch::class,
        ]);
    }
}
