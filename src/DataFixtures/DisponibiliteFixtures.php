<?php

namespace App\DataFixtures;

use App\Entity\Disponibilite;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DisponibiliteFixtures extends Fixture implements DependentFixtureInterface
{
	public function load(ObjectManager $manager): void
	{
		/** @var User[] $praticiens */
		$praticiens = $manager->getRepository(User::class)->findAll();

		if (count($praticiens) === 0) {
			throw new \RuntimeException('Aucun user trouvé pour créer des disponibilités');
		}

		foreach ($praticiens as $praticien) {

			// Lundi (1) à Vendredi (5)
			for ($jour = 1; $jour <= 5; $jour++) {

				// 🕘 Matin
				$matin = new Disponibilite();
				$matin->setPraticien($praticien);
				$matin->setJourSemaine($jour);
				$matin->setHeureDebut(new \DateTime('09:00'));
				$matin->setHeureFin(new \DateTime('12:00'));
				$matin->setDureeCreneau(20);
				$matin->setActif(true);
				$manager->persist($matin);

				// 🕑 Après-midi
				$aprem = new Disponibilite();
				$aprem->setPraticien($praticien);
				$aprem->setJourSemaine($jour);
				$aprem->setHeureDebut(new \DateTime('14:00'));
				$aprem->setHeureFin(new \DateTime('18:00'));
				$aprem->setDureeCreneau(20);
				$aprem->setActif(true);
				$manager->persist($aprem);
			}
		}

		$manager->flush();
	}

	public function getDependencies(): array
	{
		return [
			UserFixtures::class,
		];
	}
}
