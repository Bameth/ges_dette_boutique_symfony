<?php

namespace App\Form;

use App\Entity\Dette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DetteSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('telephone', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez le numéro de téléphone',
                    'class' => 'border border-gray-300 rounded-md p-2',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner un numéro de téléphone valide.']),
                    new Regex('/^(77|78|76)[0-9]{7}$/', 'Numéro de téléphone non valide.'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dette::class,
        ]);
    }
}
