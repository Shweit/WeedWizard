<?php

namespace App\Form;

use App\Entity\CannabisVerein;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CannabisVereinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Name des Vereins',
                'constraints' => [
                    new NotBlank(
                        message: 'Bitte gib einen gültigen Namen ein.',
                    ),
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank(message: 'Bitte gib eine Adresse ein.'),
                    new Length(['min' => 3, 'max' => 255]),
                ],
            ])
            ->add('mapbox_id', HiddenType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('strasse', TextType::class, [
                'required' => true,
                'label' => 'Straße',
                'disabled' => true,
                'constraints' => [
                    new NotBlank(
                        message: 'Bitte gib eine gültige Straße ein.',
                    ),
                ],
            ])
            ->add('hausnummer', TextType::class, [
                'required' => true,
                'label' => 'Hausnummer',
                'disabled' => true,
                'constraints' => [
                    new NotBlank(
                        message: 'Bitte gib eine gültige Hausnummer ein.',
                    ),
                ],
            ])
            ->add('plz', IntegerType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'PLZ',
                'disabled' => true,
                'constraints' => [
                    new NotBlank(
                        message: 'Bitte gib eine gültige PLZ ein.',
                    ),
                    new Length(
                        min: 5,
                        max: 5,
                        minMessage: 'Die PLZ muss genau 5 Ziffern lang sein.',
                        maxMessage: 'Die PLZ muss genau 5 Ziffern lang sein.',
                    ),
                ],
            ])
            ->add('ort', TextType::class, [
                'required' => true,
                'disabled' => true,
                'label' => 'Ort',
                'constraints' => [
                    new NotBlank(
                        message: 'Bitte gib einen gültigen Ort ein.',
                    ),
                ],
            ])
            ->add('adresszusatz', TextType::class, [
                'required' => false,
                'label' => 'Adresszusatz',
            ])
            ->add('website', UrlType::class, [
                'required' => false,
                'label' => 'Website',
            ])
            ->add('mitgliedsbeitrag', MoneyType::class, [
                'required' => true,
                'label' => 'Mitgliedsbeitrag',
                'currency' => 'EUR',
                'constraints' => [
                    new NotBlank(
                        message: 'Bitte gib einen gültigen Mitgliedsbeitrag ein.',
                    ),
                ],
            ])
            ->add('beschreibung', TextType::class, [
                'required' => false,
                'label' => 'Beschreibung',
            ])
            ->add('sonstiges', TextareaType::class, [
                'required' => false,
                'label' => 'Sonstiges',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Verein erstellen',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CannabisVerein::class,
        ]);
    }
}
