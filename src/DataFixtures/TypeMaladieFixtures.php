<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\TypeMaladie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeMaladieFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$types = [

			// 🫀 Maladies chroniques
			'Diabète',
			'Hypertension artérielle',
			'Asthme',
			'Insuffisance cardiaque',
			'Maladie coronarienne',
			'BPCO',
			'Insuffisance rénale chronique',

			// 🧠 Neurologie
			'Épilepsie',
			'Maladie de Parkinson',
			'Maladie d’Alzheimer',
			'Migraine chronique',
			'AVC',

			// 🦴 Rhumatologie
			'Arthrose',
			'Polyarthrite rhumatoïde',
			'Lupus',
			'Spondylarthrite ankylosante',
			'Goutte',

			// 🦠 Infectieuses
			'Tuberculose',
			'Hépatite B',
			'Hépatite C',
			'VIH / Sida',
			'COVID-19',
			'Paludisme',

			// 🧬 Endocrinologie
			'Hypothyroïdie',
			'Hyperthyroïdie',
			'Syndrome métabolique',

			// 🫁 Respiratoires
			'Bronchite chronique',
			'Pneumonie',
			'Apnée du sommeil',

			// 🧠 Psychiatrie
			'Dépression',
			'Trouble anxieux',
			'Schizophrénie',
			'Trouble bipolaire',

			// 🩺 Digestif
			'Gastrite',
			'Ulcère gastrique',
			'Maladie de Crohn',
			'Rectocolite hémorragique',

			// 🧪 Autres
			'Anémie',
			'Allergie chronique',
			'Cancer',
			'Obésité',
			'Maladie auto-immune',
		];

		foreach ($types as $nom) {
			$type = new TypeMaladie();
			$type->setNom($nom);
			$manager->persist($type);
		}

		$manager->flush();
	}
}
