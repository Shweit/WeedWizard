<?php

namespace App\Form;

use App\Entity\BudBash;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class BudBashType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Wie heißt die Party?',
                'constraints' => [
                    new NotBlank(message: 'Bitte gib einen Namen ein.'),
                    new Length(['min' => 3, 'max' => 255]),
                ],
            ])
            ->add('start', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Wann steigt die Party?',
                'data' => new \DateTime('tomorrow 18:00'),
                'constraints' => [
                    new NotBlank(message: 'Bitte gib ein Datum und eine Uhrzeit ein.'),
                    new GreaterThanOrEqual('today'), // Check if date is in the future
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Wo steigt die Party?',
                'constraints' => [
                    new NotBlank(message: 'Bitte gib eine Adresse ein.'),
                    new Length(['min' => 3, 'max' => 255]),
                ],
            ])
            ->add('mapbox_id', HiddenType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('address_street', TextType::class, [
                'mapped' => false,
                'label' => 'Straße',
                'disabled' => true,
                'constraints' => [
                    new NotBlank(message: 'Bitte gib eine Straße ein.'),
                    new Length(['min' => 3, 'max' => 255]),
                ],
            ])
            ->add('address_house_number', TextType::class, [
                'mapped' => false,
                'label' => 'Hausnummer',
                'disabled' => true,
                'constraints' => [
                    new NotBlank(message: 'Bitte gib eine Hausnummer ein.'),
                    new Length(['min' => 1, 'max' => 10]),
                ],
            ])
            ->add('address_city', TextType::class, [
                'mapped' => false,
                'label' => 'Stadt',
                'disabled' => true,
                'constraints' => [
                    new NotBlank(message: 'Bitte gib eine Stadt ein.'),
                    new Length(['min' => 3, 'max' => 255]),
                ],
            ])
            ->add('address_postal_code', TextType::class, [
                'mapped' => false,
                'label' => 'Postleitzahl',
                'disabled' => true,
                'constraints' => [
                    new NotBlank(message: 'Bitte gib eine Postleitzahl ein.'),
                    new Length(['min' => 5, 'max' => 5]),
                ],
            ])
            ->add('entrance_fee', MoneyType::class, [
                'label' => 'Wie viel kostet der Eintritt?',
                'currency' => 'EUR',
                'data' => 0,
                'constraints' => [
                    new NotBlank(message: 'Bitte gib einen Betrag ein.'),
                    new GreaterThanOrEqual(0, message: 'Bitte gib einen positiven Betrag ein.'),
                    new LessThanOrEqual(1000, message: 'Der Betrag darf nicht höher als 1000€ sein.'),
                ],
            ])
            ->add('CheckAttendances', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Willst du die Anwesenheit der Gäste überprüfen?',
                'required' => false,
                'help' => 'Wenn du diese Option aktivierst, kannst du ab Start der Party die Anwesenheit der Gäste mit einem QR Code überprüfen. Dies ist besonders nützlich, wenn du eine Gästeliste hast und nicht willst dass ungebetene Gäste deine Party crashen.',
            ])
            ->add('extraInfo', TextareaType::class, [
                'label' => 'Möchtest du noch etwas hinzufügen?',
                'required' => false,
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Party erstellen',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BudBash::class,
        ]);
    }
}
