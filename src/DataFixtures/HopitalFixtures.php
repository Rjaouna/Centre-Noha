<?php

namespace App\DataFixtures;

use App\Entity\Hopital;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HopitalFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$file = __DIR__ . '/../../public/assets/doc/repartition-des-hopitaux-par-region-et-province-2022.csv';

		if (!file_exists($file)) {
			throw new \Exception("Le fichier CSV est introuvable : " . $file);
		}

		// 🔁 Mapping abréviation → libellé complet
		$mapping = [
			'HP'    => 'Hôpital Provincial/Préfectoral',
			'HR'    => 'Hôpital Régional',
			'HIR'   => 'Hôpital Interrégional',
			'HPr'   => 'Hôpital de Proximité',
			'HPsyP' => 'Hôpital Psychiatrique Provincial/Préfectoral',
			'CRO'   => 'Centre Régional d\'Oncologie',
			'HPsyR' => 'Hôpital Psychiatrique Régional',
			'CPU'   => 'Centre Psychiatrique Universitaire',
		];

		$handle = fopen($file, 'r');

		// sauter les en-têtes
		fgetcsv($handle, 0, ',');

		while (($row = fgetcsv($handle, 0, ',')) !== false) {

			if (count($row) < 5) continue;

			[$region, $delegation, $commune, $etablissement, $categorie] = $row;

			// 🔄 Convertir catégorie abrégée → texte complet
			$categorieFull = $mapping[$categorie] ?? $categorie;

			$hopital = new Hopital();
			$hopital->setRegion($region);
			$hopital->setDelegation($delegation);
			$hopital->setCommune($commune);
			$hopital->setEtablissement($etablissement);
			$hopital->setCategorie($categorieFull);

			$manager->persist($hopital);
		}

		fclose($handle);

		$manager->flush();
	}
}
