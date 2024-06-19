<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Vorname',
                'attr' => ['autocomplete' => 'firstname'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Bitte gib deinen Vornamen an.',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nachname',
                'attr' => ['autocomplete' => 'lastname'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Bitte gib deinen Nachnamen an.',
                    ]),
                ],
            ])
            ->add('username', TextType::class, [
                'label' => 'Benutzername',
                'attr' => ['autocomplete' => 'username'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Bitte gib deinen Benutzernamen an.',
                    ]),
                ],
            ])
            ->add('profilePicture', FileType::class, [
                'label' => 'Profilbild',
                'required' => false,
                'attr' => ['accept' => 'image/*'],
                'row_attr' => ['style' => 'display: none;'],
                'mapped' => false,
            ])
            ->add('banner', FileType::class, [
                'label' => 'Banner',
                'required' => false,
                'attr' => ['accept' => 'image/*'],
                'row_attr' => ['style' => 'display: none;'],
                'mapped' => false,
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Biografie',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
