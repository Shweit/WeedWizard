<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Vorname',
                'attr' => [
                    'autocomplete' => 'firstname',
                    'class' => 'form-control rounded-5',
                    'placeholder' => 'Vorname',
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Bitte gib deinen Vornamen an.',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nachname',
                'attr' => [
                    'autocomplete' => 'lastname',
                    'class' => 'form-control rounded-5',
                    'placeholder' => 'Nachname',
                ],
                'required' => false,
            ])
            ->add('birthdate', DateType::class, [
                'label' => 'Geburtstag',
                'attr' => [
                    'autocomplete' => 'birthdate',
                    'class' => 'form-control rounded-5',
                    'placeholder' => 'Geburtsdatum',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Bitte gib dein Geburtsdatum an.',
                    ]),
                    new LessThanOrEqual('-18 years', message: 'Du musst mindestens 18 Jahre alt sein.'),
                ],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-Mail Adresse',
                'attr' => [
                    'autocomplete' => 'email',
                    'class' => 'form-control rounded-5',
                    'placeholder' => 'Email',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Bitte gib eine E-Mail Adresse an.',
                    ]),
                    new Email([
                        'message' => 'Die E-Mail Adresse "{{ value }}" ist keine gültige E-Mail Adresse.',
                    ]),
                ],
                'required' => true,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Ich stimme den AGB\'s zu',
                'attr' => ['class' => 'form-check-input'],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Du musst unseren AGB zustimmen.',
                    ]),
                ],
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Die Passwörter müssen übereinstimmen.',
                'required' => true,
                'first_options' => [
                    'label' => 'Passwort',
                    'attr' => [
                        'autocomplete' => 'password',
                        'class' => 'form-control rounded-5',
                        'placeholder' => 'Passwort',
                    ],
                ],
                'second_options' => [
                    'label' => 'Passwort wiederholen',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control rounded-5',
                        'placeholder' => 'Passwort wiederholen',
                    ],
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
