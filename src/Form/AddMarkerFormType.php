<?php

namespace App\Form;

use App\Entity\MapMarkers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddMarkerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'constraints' => [
                    new NotBlank(message: 'Bitte gebe deinem Marker ein Titel.'),
                    new Length(['min' => 3, 'max' => 255]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Beschreibung',
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ])
            ->add('coordinates', TextType::class, [
                'label' => 'Koordinaten',
                'disabled' => true,
                'constraints' => [
                    new NotBlank(message: 'Bitte gebe die Koordinaten ein.'),
                ],
            ])
            ->add('public', CheckboxType::class, [
                'label' => 'Öffentlich sichtbar',
                'help' => 'Wenn du den Marker öffentlich sichtbar machst, können andere Benutzer deinen Marker sehen.',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MapMarkers::class,
        ]);
    }
}
