<?php

namespace App\DataFixtures;

use App\Entity\PrestationPrice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PrestationPriceFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$prestations = [
			['Consultation générale', 'Consultation médicale standard.', 'Consultation', 20.0, '25.00'],
			['Consultation de contrôle', 'Suivi après traitement.', 'Consultation', 15.0, '20.00'],
			['Consultation spécialisée', 'Avis spécialisé / approfondi.', 'Consultation', 30.0, '45.00'],

			['Échographie abdominale', 'Échographie des organes abdominaux.', 'Échographie', 25.0, '60.00'],
			['Échographie pelvienne', 'Échographie pelvienne.', 'Échographie', 25.0, '60.00'],
			['Échographie obstétricale', 'Suivi échographique de grossesse.', 'Échographie', 30.0, '75.00'],

			['Radiographie thorax', 'Radio poumons / cage thoracique.', 'Radiologie', 10.0, '35.00'],
			['Radiographie membre', 'Radio bras/jambe selon indication.', 'Radiologie', 10.0, '30.00'],
			['Radiographie lombaire', 'Radio rachis lombaire.', 'Radiologie', 12.0, '40.00'],

			['ECG', 'Électrocardiogramme.', 'Cardiologie', 15.0, '30.00'],
			['Prise de sang (simple)', 'Prélèvement sanguin standard.', 'Laboratoire', 10.0, '15.00'],
			['Pansement / soin', 'Soin infirmier / pansement.', 'Soins', 15.0, '18.00'],
		];

		foreach ($prestations as [$nom, $desc, $cat, $duree, $prix]) {
			$p = new PrestationPrice();
			$p->setNom($nom);
			$p->setDescription($desc);
			$p->setCategorie($cat);
			$p->setDureeMinutes($duree);
			$p->setPrix($prix);

			$manager->persist($p);
		}

		$manager->flush();
	}
}
