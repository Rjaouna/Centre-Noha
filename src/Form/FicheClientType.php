<?php

namespace App\Form;

use App\Entity\FicheClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

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
                'Agadir' => 'Agadir',
                'Ahfir' => 'Ahfir',
                'Al Hoceima' => 'Al Hoceima',
                'Asilah' => 'Asilah',
                'Azemmour' => 'Azemmour',
                'Azilal' => 'Azilal',
                'Béni Mellal' => 'Béni Mellal',
                'Berkane' => 'Berkane',
                'Berrechid' => 'Berrechid',
                'Bouarfa' => 'Bouarfa',
                'Boujdour' => 'Boujdour',
                'Boulemane' => 'Boulemane',
                'Casablanca' => 'Casablanca',
                'Chefchaouen' => 'Chefchaouen',
                'Chichaoua' => 'Chichaoua',
                'Dakhla' => 'Dakhla',
                'El Jadida' => 'El Jadida',
                'El Kelaâ des Sraghna' => 'El Kelaâ des Sraghna',
                'Erfoud' => 'Erfoud',
                'Errachidia' => 'Errachidia',
                'Essaouira' => 'Essaouira',
                'Fès' => 'Fès',
                'Figuig' => 'Figuig',
                'Guelmim' => 'Guelmim',
                'Guercif' => 'Guercif',
                'Ifrane' => 'Ifrane',
                'Imzouren' => 'Imzouren',
                'Jerada' => 'Jerada',
                'Kénitra' => 'Kénitra',
                'Khemisset' => 'Khemisset',
                'Khénifra' => 'Khénifra',
                'Khouribga' => 'Khouribga',
                'Ksar El Kebir' => 'Ksar El Kebir',
                'Laâyoune' => 'Laâyoune',
                'Larache' => 'Larache',
                'Marrakech' => 'Marrakech',
                'Martil' => 'Martil',
                'Mediouna' => 'Mediouna',
                    'Meknès' => 'Meknès',
                'Midelt' => 'Midelt',
                'Mohammedia' => 'Mohammedia',
                'Nador' => 'Nador',
                'Oualidia' => 'Oualidia',
                'Ouarzazate' => 'Ouarzazate',
                'Ouazzane' => 'Ouazzane',
                'Ouezzane' => 'Ouezzane',
                'Oujda' => 'Oujda',
                'Rabat' => 'Rabat',
                'Safi' => 'Safi',
                'Salé' => 'Salé',
                'Sefrou' => 'Sefrou',
                'Settat' => 'Settat',
                'Sidi Bennour' => 'Sidi Bennour',
                'Sidi Ifni' => 'Sidi Ifni',
                'Sidi Kacem' => 'Sidi Kacem',
                'Sidi Slimane' => 'Sidi Slimane',
                'Skhirat' => 'Skhirat',
                'Tanger' => 'Tanger',
                'Tan-Tan' => 'Tan-Tan',
                'Taourirt' => 'Taourirt',
                'Taroudant' => 'Taroudant',
                'Taza' => 'Taza',
                'Témara' => 'Témara',
                'Tétouan' => 'Tétouan',
                'Tiflet' => 'Tiflet',
                'Tinghir' => 'Tinghir',
                'Tiznit' => 'Tiznit',
                'Youssoufia' => 'Youssoufia',
                'Zagora' => 'Zagora',
                ],

            'constraints' => [
                    new Assert\NotBlank(['message' => 'La ville est obligatoire.'])
                ]
            ])

            ->add('age', DateType::class, [
                'widget' => 'single_text',  // évite les selects jour/mois/année
                'html5' => true,
                'label' => 'Date de naissance',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])

            ->add('poids', ChoiceType::class, [
                'label' => 'Poids (kg)',
            'placeholder' => 'Sélectionnez un poids',
            'choices' => [
                '6 - 9 kg' => '6-9',
                '10 - 19 kg' => '10-19',
                '20 - 29 kg' => '20-29',
                '30 - 39 kg' => '30-39',
                '40 - 49 kg' => '40-49',
                '50 - 59 kg' => '50-59',
                '60 - 69 kg' => '60-69',
                '70 - 79 kg' => '70-79',
                '80 - 89 kg' => '80-89',
                '90 - 99 kg' => '90-99',
                '100 - 109 kg' => '100-109',
                '110 - 119 kg' => '110-119',
                '120 - 129 kg' => '120-129',
                '130 - 139 kg' => '130-139',
                '140 - 149 kg' => '140-149',
                '150 kg et +' => '150+',
            ],
                'constraints' => [
                new \Symfony\Component\Validator\Constraints\NotBlank([
                    'message' => 'Veuillez sélectionner une tranche de poids.'
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
                // Taille exacte : 10 chiffres
                new Assert\Length([
                    'min' => 10,
                    'max' => 10,
                    'exactMessage' => 'Le numéro doit contenir exactement 10 chiffres.',
                ]),
                    new Assert\Regex([
                        'pattern' => '/^(06|07)[0-9]{8}$/',
                        'message' => 'Numéro marocain invalide.',
                    ]),
                ]
            ])

            ->add('dureeMaladie', ChoiceType::class, [
                'label' => 'Durée de la maladie',
                'placeholder' => 'Sélectionnez une durée',
                'choices' => [
                    '1 à 5 jours' => '1-5',
                    '6 à 10 jours' => '6-10',
                    '11 à 20 jours' => '11-20',
                    '21 à 30 jours' => '21-30',
                    '31 à 50 jours' => '31-50',
                    '51 à 80 jours' => '51-80',
                    '81 à 100 jours' => '81-100',
                    'Plus de 100 jours' => '100+',
                ],
            ])


            ->add('typeMaladie', ChoiceType::class, [
                'label' => 'Type de maladie',
            'placeholder' => 'Sélectionnez un type de maladie',
            'choices' => [
                'Santé mentale (mal-être, anxiété, stress)' => 'sante_mentale',
                'Fièvre' => 'fievre',
                'Rhume' => 'rhume',
                'Maux de gorge' => 'maux_gorge',
                'Renouvellement et réévaluation de traitement' => 'renouvellement_traitement',
                'Consultation de suivi' => 'consultation_suivi',
                'Troubles digestifs' => 'troubles_digestifs',
                'Gêne urinaire' => 'gene_urinaire',
                'Douleur au dos' => 'douleur_dos',
                'Symptômes allergiques' => 'symptomes_allergiques',
                'Gynécologie' => 'gynecologie',
                'Problème de peau' => 'probleme_peau',
                'Maux de tête' => 'maux_tete',
                'Autre (spécifier)' => 'Autre',
            ],
                'constraints' => [
                    new Assert\NotBlank(),
            ],
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
