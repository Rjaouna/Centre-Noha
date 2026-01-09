<?php

namespace App\Form;

use App\Entity\PrestationPrice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestationPriceType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('nom', TextType::class, [
				'label' => 'Nom',
				'attr' => ['placeholder' => 'Ex: Consultation générale'],
			])
			->add('categorie', TextType::class, [
				'label' => 'Catégorie',
				'attr' => ['placeholder' => 'Ex: Consultation'],
			])
			->add('prix', MoneyType::class, [
				'label' => 'Prix',
				'currency' => 'EUR',
				'scale' => 2,
				'attr' => ['placeholder' => 'Ex: 25,00'],
			])
			->add('duree_minutes', NumberType::class, [
				'label' => 'Durée (minutes)',
				'scale' => 1,
			])
			->add('description', TextareaType::class, [
				'label' => 'Description',
				'required' => false,
				'attr' => ['rows' => 3],
			])
		;
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => PrestationPrice::class,
		]);
	}
}
