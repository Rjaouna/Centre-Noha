<?php

namespace App\Form;

use App\Entity\Paiement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaiementType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('prixTotal')
			->add('montantPaye')
			->add('reste')
			->add('typePaiement', ChoiceType::class, [
				'choices' => [
					'Cash' => 'cash',
					'Carte' => 'carte',
					'Virement' => 'virement',
				],
				'placeholder' => 'Choisir...',
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
