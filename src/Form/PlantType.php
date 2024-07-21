<?php

namespace App\Form;

use App\Services\WeedWizardKernel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlantType extends AbstractType
{
    public function __construct(
        private readonly WeedWizardKernel $weedWizardKernel
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Name der Pflanze'])
            ->add('breeder', ChoiceType::class, [
                'choices' => $this->weedWizardKernel->getBreederChoices(),
            ])
            ->add('strain', ChoiceType::class, [
                'choices' => $this->weedWizardKernel->getStrainChoices(),
            ])

            ->add('date', DateType::class, [
                'label' => 'Anbaudatum',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(message: 'Bitte gib ein Datum ein.'),
                    new LessThanOrEqual('today'),
                ],
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'Wachstumsstatus',
                'choices' => [
                    '' => null,
                    'Sämling' => 'seedling',
                    'Vegetativ' => 'vegetative',
                    'Blüte' => 'flowering',
                    'Ernte' => 'harvest',
                ],
            ])
            ->add('placeOfCultivation', ChoiceType::class, [
                'label' => 'Ort der Anpflanzung',
                'choices' => [
                    '' => null,
                    'Innen' => 'indoor',
                    'Außen' => 'outdoor',
                ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Speichern']);
    }

    public function configureOptions(OptionsResolver $resolver): void {}
}
