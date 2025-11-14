<?php

namespace App\Form;

use App\Entity\FicheClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' => 'Nom complet',
                'attr' => [
                    'placeholder' => 'Ex : Ahmed El Amrani'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est obligatoire.']),
                    new Assert\Length(['min' => 2, 'minMessage' => 'Le nom est trop court.']),
                ]
            ])

            ->add('ville', ChoiceType::class, [
                'label' => 'Ville',
                'placeholder' => 'Sélectionnez une ville',
                'choices' => [
                    'Casablanca' => 'Casablanca',
                    'Rabat' => 'Rabat',
                    'Marrakech' => 'Marrakech',
                    'Agadir' => 'Agadir',
                    'Tanger' => 'Tanger',
                    'Fès' => 'Fès',
                    'Meknès' => 'Meknès',
                    'Tétouan' => 'Tétouan',
                    'Oujda' => 'Oujda',
                    'El Jadida' => 'El Jadida',
                    'Safi' => 'Safi',
                    'Mohammedia' => 'Mohammedia',
                    'Nador' => 'Nador',
                    'Khouribga' => 'Khouribga',
                    'Laâyoune' => 'Laâyoune',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La ville est obligatoire.'])
                ]
            ])

            ->add('age', null, [
                'label' => 'Âge',
                'attr' => ['placeholder' => 'Ex : 30'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Type('integer'),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 120,
                        'notInRangeMessage' => 'L’âge doit être entre 1 et 120 ans.',
                    ])
                ]
            ])

            ->add('poids', null, [
                'label' => 'Poids (kg)',
                'attr' => ['placeholder' => 'Ex : 72'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Type('numeric'),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 250,
                        'notInRangeMessage' => 'Le poids doit être entre 1 et 250 kg.'
                    ])
                ]
            ])

            ->add('telephone', null, [
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Ex : 0612345678'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^(06|07)[0-9]{8}$/',
                        'message' => 'Numéro marocain invalide.',
                    ]),
                ]
            ])

            ->add('dureeMaladie', null, [
                'label' => 'Durée de la maladie (jours)',
                'attr' => ['placeholder' => 'Ex : 15'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Type('integer'),
                    new Assert\Range([
                        'min' => 0,
                        'max' => 10000,
                    ])
                ]
            ])

            ->add('typeMaladie', null, [
                'label' => 'Type de maladie',
                'attr' => ['placeholder' => 'Ex : Diabète, tension, allergie…'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3]),
                ]
            ])

            ->add('traitement', null, [
                'label' => 'Traitement (si existant)',
                'attr' => ['placeholder' => 'Décrivez le traitement…'],
                'required' => false
            ])

            ->add('observation', null, [
                'label' => 'Observation',
                'attr' => ['placeholder' => 'Notes supplémentaires…'],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheClient::class,
        ]);
    }
}
