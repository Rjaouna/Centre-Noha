<?php

namespace App\DataFixtures;

use App\Entity\Analyse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnalyseFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$analyses = [

			// 🩸 Hématologie
			['NFS (Numération Formule Sanguine)', 'Analyse complète des cellules sanguines'],
			['Hémoglobine', 'Mesure du taux d’hémoglobine dans le sang'],
			['Plaquettes', 'Évaluation du nombre de plaquettes sanguines'],
			['VS (Vitesse de sédimentation)', 'Indicateur d’inflammation'],

			// 🧪 Biochimie
			['Glycémie à jeun', 'Mesure du taux de sucre dans le sang'],
			['HbA1c', 'Équilibre glycémique sur 3 mois'],
			['Créatinine', 'Évaluation de la fonction rénale'],
			['Urée', 'Exploration de la fonction rénale'],
			['Bilan lipidique', 'Cholestérol total, HDL, LDL, triglycérides'],
			['Transaminases (ASAT/ALAT)', 'Exploration de la fonction hépatique'],
			['Gamma GT', 'Bilan hépatique et consommation alcoolique'],
			['Bilirubine', 'Exploration hépatique et hémolyse'],

			// 🦠 Inflammation / Infection
			['CRP (Protéine C-réactive)', 'Marqueur de l’inflammation'],
			['Procalcitonine', 'Marqueur des infections bactériennes'],
			['Fibrinogène', 'Marqueur inflammatoire et de coagulation'],

			// 🧬 Endocrinologie
			['TSH', 'Exploration de la fonction thyroïdienne'],
			['T3', 'Hormone thyroïdienne'],
			['T4', 'Hormone thyroïdienne'],
			['Cortisol', 'Exploration surrénalienne'],
			['Vitamine D', 'Évaluation du statut en vitamine D'],

			// 🧠 Cardiologie
			['Troponine', 'Diagnostic de l’infarctus du myocarde'],
			['BNP', 'Insuffisance cardiaque'],

			// 🧫 Coagulation
			['TP (Temps de prothrombine)', 'Évaluation de la coagulation'],
			['INR', 'Surveillance des traitements anticoagulants'],
			['TCA', 'Exploration de la coagulation'],

			// 🧪 Urines
			['ECBU', 'Examen cytobactériologique des urines'],
			['Bandelette urinaire', 'Dépistage urinaire rapide'],
			['Protéinurie', 'Recherche de protéines dans les urines'],

			// 🦠 Sérologies
			['Sérologie VIH', 'Dépistage du VIH'],
			['Sérologie Hépatite B', 'Dépistage de l’hépatite B'],
			['Sérologie Hépatite C', 'Dépistage de l’hépatite C'],
			['Sérologie Syphilis', 'Dépistage de la syphilis'],

			// 👶 Divers
			['Test de grossesse (β-HCG)', 'Dosage de l’hormone de grossesse'],
			['PSA', 'Dépistage et suivi de la prostate'],
			['Fer sérique', 'Évaluation des réserves en fer'],
			['Ferritine', 'Stock du fer dans l’organisme'],
		];

		foreach ($analyses as [$name, $description]) {
			$analyse = new Analyse();
			$analyse->setName($name);
			$analyse->setDescription($description);

			$manager->persist($analyse);
		}

		$manager->flush();
	}
}
