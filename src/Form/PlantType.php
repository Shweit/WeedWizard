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
            ->add('type', ChoiceType::class, [
                'label' => 'Pflanzentyp',
                'choices' => [
                    '' => null,
                    'Indica' => 'indica',
                    'Sativa' => 'sativa',
                    'Hybrid' => 'hybrid'
                ]
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
            ->add('lighting', ChoiceType::class, [
                'label' => 'Beleuchtung',
                'choices' => [
                    '' => null,
                    'Sonnenlicht' => 'sunlight',
                    'Lampe' => 'lamp'
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

