<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Range;

class CannaDoseCalculatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('basis_dosage', ChoiceType::class, [
                'label' => 'Basisdosierung',
                'choices' => [
                    'Leicht' => 300,
                    'Mittel' => 400,
                    'Stark' => 500,
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Die Basisdosierung darf nicht leer sein.'
                    ),
                    new PositiveOrZero(
                        message: 'Die Basisdosierung muss eine positive Zahl sein.'
                    ),
                ],
            ])
            ->add('experience', ChoiceType::class, [
                'label' => 'Erfahrung',
                'choices' => [
                    'Anfänger' => 0.8,
                    'Gelegenheitsnutzer' => 1.0,
                    'Regelmäßiger Nutzer' => 1.4,
                    'Schwerer Nutzer' => 1.8,
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Die Erfahrung darf nicht leer sein.'
                    ),
                    new Positive(
                        message: 'Die Erfahrung muss eine positive Zahl sein.'
                    ),
                ],
            ])
            ->add('intensity', RangeType::class, [
                'label' => 'Intensität',
                'help_html' => true,
                'help' => 'Ausgewählte Intensität: <output id="intensity-value">Niedrig</output>',
                'attr' => [
                    'min' => 1,
                    'max' => 20,
                ],
                'data' => 5,
                'constraints' => [
                    new NotBlank(
                        message: 'Die Intensität darf nicht leer sein.'
                    ),
                    new Positive(
                        message: 'Die Intensität muss eine positive Zahl sein.'
                    ),
                    new Range(
                        notInRangeMessage: 'Die Intensität muss zwischen {{ min }} und {{ max }} liegen.',
                        min: 1,
                        max: 20
                    ),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Berechnen',
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
