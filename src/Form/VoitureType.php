<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la voiture'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 5
                ]
            ])
            ->add('prixQuotidien', NumberType::class, [
                'label' => 'Prix quotidien (€)',
                'attr' => [
                    'step' => '0.01'
                ]
            ])
            ->add('prixMensuel', NumberType::class, [
                'label' => 'Prix mensuel (€)',
                'attr' => [
                    'step' => '0.01'
                ]
            ])
            ->add('places', ChoiceType::class, [
                'label' => 'Nombre de places',
                'choices' => array_combine(range(1, 9), range(1, 9)),// Génère des choix de 1 à 9
                'data' => 1,
            ])

            ->add('manuelle', ChoiceType::class, [
                'label' => 'Type de transmission',
                'choices' => [
                    'Manuelle' => true,
                    'Automatique' => false
                ],
                'data' => true // Valeur par défaut
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
