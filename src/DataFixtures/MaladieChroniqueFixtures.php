<?php

namespace App\DataFixtures;

use App\Entity\MaladieChronique;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MaladieChroniqueFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$maladies = [

			// 🫀 Cardiovasculaires
			[
				'nom' => 'Hypertension artérielle',
				'description' => 'Élévation chronique de la pression artérielle augmentant le risque cardiovasculaire.'
			],
			[
				'nom' => 'Insuffisance cardiaque',
				'description' => 'Incapacité du cœur à assurer un débit sanguin suffisant.'
			],
			[
				'nom' => 'Maladie coronarienne',
				'description' => 'Réduction du flux sanguin vers le muscle cardiaque.'
			],

			// 🩸 Métaboliques
			[
				'nom' => 'Diabète de type 1',
				'description' => 'Maladie auto-immune caractérisée par une absence de production d’insuline.'
			],
			[
				'nom' => 'Diabète de type 2',
				'description' => 'Résistance à l’insuline associée à une production insuffisante.'
			],
			[
				'nom' => 'Obésité',
				'description' => 'Accumulation excessive de graisse corporelle ayant des effets néfastes sur la santé.'
			],

			// 🫁 Respiratoires
			[
				'nom' => 'Asthme',
				'description' => 'Inflammation chronique des voies respiratoires provoquant des crises de dyspnée.'
			],
			[
				'nom' => 'BPCO',
				'description' => 'Bronchopneumopathie chronique obstructive avec limitation progressive du débit aérien.'
			],

			// 🧠 Neurologiques
			[
				'nom' => 'Maladie d’Alzheimer',
				'description' => 'Maladie neurodégénérative entraînant un déclin progressif des fonctions cognitives.'
			],
			[
				'nom' => 'Maladie de Parkinson',
				'description' => 'Affection neurologique chronique affectant les mouvements.'
			],
			[
				'nom' => 'Épilepsie',
				'description' => 'Trouble neurologique caractérisé par des crises récurrentes.'
			],

			// 🦴 Inflammatoires / auto-immunes
			[
				'nom' => 'Polyarthrite rhumatoïde',
				'description' => 'Maladie inflammatoire chronique des articulations.'
			],
			[
				'nom' => 'Maladie de Crohn',
				'description' => 'Maladie inflammatoire chronique du tube digestif.'
			],
			[
				'nom' => 'Psoriasis',
				'description' => 'Maladie inflammatoire chronique de la peau.'
			],

			// 🧬 Endocriniennes
			[
				'nom' => 'Hypothyroïdie',
				'description' => 'Diminution de la production des hormones thyroïdiennes.'
			],
			[
				'nom' => 'Hyperthyroïdie',
				'description' => 'Production excessive d’hormones thyroïdiennes.'
			],

			// 🧫 Infectieuses chroniques
			[
				'nom' => 'VIH',
				'description' => 'Infection chronique affectant le système immunitaire.'
			],
			[
				'nom' => 'Hépatite B chronique',
				'description' => 'Inflammation persistante du foie due au virus de l’hépatite B.'
			],
			[
				'nom' => 'Hépatite C chronique',
				'description' => 'Infection virale chronique du foie.'
			],

			// 🧠 Santé mentale
			[
				'nom' => 'Dépression chronique',
				'description' => 'Trouble de l’humeur persistant impactant la qualité de vie.'
			],
			[
				'nom' => 'Trouble bipolaire',
				'description' => 'Trouble psychiatrique caractérisé par des variations extrêmes de l’humeur.'
			],

			// 🦠 Autres
			[
				'nom' => 'Insuffisance rénale chronique',
				'description' => 'Altération progressive et irréversible de la fonction rénale.'
			],
			[
				'nom' => 'Ostéoporose',
				'description' => 'Fragilisation des os augmentant le risque de fractures.'
			],
			[
				'nom' => 'Endométriose',
				'description' => 'Présence de tissu endométrial en dehors de l’utérus.'
			],
			[
				'nom' => 'Fibromyalgie',
				'description' => 'Syndrome douloureux chronique diffus.'
			],
			[
				'nom' => 'Cancer (forme chronique)',
				'description' => 'Certaines formes de cancer évoluent comme des maladies chroniques.'
			],
		];

		foreach ($maladies as $data) {
			$maladie = new MaladieChronique();
			$maladie->setNom($data['nom']);
			$maladie->setDescription($data['description']);
			$manager->persist($maladie);
		}

		$manager->flush();
	}
}
