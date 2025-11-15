<?php

namespace App\Form;

use App\Entity\FicheClient;
use App\Entity\SuiviSoin;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuiviSoinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('diagnostic')
            ->add('createdAt', null, [
                'widget' => 'single_text'
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text'
            ])
            ->add('patient', EntityType::class, [
                'class' => FicheClient::class,
                'choice_label' => 'id',
            ])
            ->add('createdBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('updatedBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SuiviSoin::class,
        ]);
    }
}
