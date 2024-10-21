<?php

namespace App\Form;

use App\Enum\StatusDettes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetteFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statusDettes',ChoiceType::class,[
                'choices' => [
                    'Tous'=> 'null',
                    'PAYEE'=> StatusDettes::PAYEE,
                    'IMPAYE'=> StatusDettes::IMPAYE,
                ],
                'label' => 'Filter',
                // 'expanded' => true,
                // 'multiple' => true,
               'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
