<?php

namespace App\Form;

use App\Entity\Paiement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Paiement1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prixTotal', NumberType::class, [
                'attr' => ['placeholder' => 'Prix total'],
                'label' => 'Prix total',
            ])
            ->add('montantPaye', NumberType::class, [
                'attr' => ['placeholder' => 'Montant payé'],
                'label' => 'Montant payé',
            ])
            ->add('reste', NumberType::class, [
                'attr' => ['placeholder' => 'Reste à payer'],
                'label' => 'Reste',
            ])
            ->add('typePaiement', ChoiceType::class, [
                'choices' => [
                    'Espèces' => 'Espèces',
                    'Carte bancaire' => 'CB',
                    'Virement' => 'Virement',
                ],
                'placeholder' => 'Sélectionnez un type',
                'label' => 'Type de paiement',
                'attr' => ['class' => 'form-select'], // important !
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
