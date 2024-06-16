<?php

namespace App\Form;

use App\Entity\Plant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name', TextType::class, ['label' => 'Name der Pflanze'])
            ->add('breeder', ChoiceType::class, [
                'choices' => [
                    'Breeder 1' => 'breeder1',
                    'Breeder 2' => 'breeder2',
                ],
                'placeholder' => 'Wählen Sie einen Breeder',
                'attr' => ['class' => 'select2article'],
            ])
            ->add('strain', ChoiceType::class, [
                'choices' => [
                    'Strain 1' => 'strain1',
                    'Strain 2' => 'strain2',
                    'Strain 3' => 'strain3',
                    'Strain 4' => 'strain4',
                ],
                'placeholder' => 'Wählen Sie einen Strain',
            ])

            ->add('date', DateType::class, [
                'label' => 'Anbaudatum',
                'widget' => 'single_text'
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'Wachstumsstatus',
                'choices' => [
                    '' => null,
                    'Sämling' => 'seedling',
                    'Vegetativ' => 'vegetative',
                    'Blüte' => 'flowering',
                    'Ernte' => 'harvest'
                ]
            ])
            ->add('placeOfCultivation', ChoiceType::class, [
                'label' => 'Ort der Anpflanzung',
                'choices' => [
                    '' => null,
                    'Innen' => 'indoor',
                    'Außen' => 'outdoor'
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => 'Speichern']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plant::class,
        ]);
    }
}

