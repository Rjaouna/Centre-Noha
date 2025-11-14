<?php

namespace App\DataFixtures;

use App\Entity\FicheClient;
use App\Entity\TroublesDigestifs;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FicheClientFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$faker = Factory::create(); // Pas fr_FR, car on va surcharger manuellement

		// 🇲🇦 Noms marocains
		$nomsMaroc = [
			'El Fassi',
			'Bennani',
			'Alaoui',
			'El Idrissi',
			'Amrani',
			'Chakiri',
			'Soufiani',
			'Fakhar',
			'Zerhouni',
			'Bouhassoun',
			'El Ouardi',
			'Haddad',
			'Bakkali',
			'Sbai'
		];

		// 🇲🇦 Prénoms hommes
		$prenomsH = [
			'Mohamed',
			'Youssef',
			'Othmane',
			'Hamza',
			'Ismail',
			'Saad',
			'Anas',
			'Ayoub',
			'Khalid',
			'Mehdi',
			'Nassim',
			'Imran',
			'Adil',
			'Houssam'
		];

		// 🇲🇦 Prénoms femmes
		$prenomsF = [
			'Fatima',
			'Khadija',
			'Zahra',
			'Imane',
			'Salma',
			'Houda',
			'Rania',
			'Safae',
			'Aya',
			'Yasmine',
			'Nour',
			'Ilham',
			'Amina',
			'Soukaina'
		];

		// 🇲🇦 Villes marocaines
		$villes = [
			'Casablanca',
			'Rabat',
			'Fès',
			'Marrakech',
			'Tanger',
			'Agadir',
			'Oujda',
			'Khouribga',
			'El Jadida',
			'Tétouan',
			'Meknès',
			'Safi',
			'Béni Mellal',
			'Nador'
		];

		for ($i = 1; $i <= 40; $i++) {

			// 🔹 Choisir homme ou femme
			$isMale = rand(0, 1);

			$prenom = $isMale
				? $faker->randomElement($prenomsH)
				: $faker->randomElement($prenomsF);

			$nomComplet = $prenom . ' ' . $faker->randomElement($nomsMaroc);

			$client = new FicheClient();
			$client->setNom($nomComplet)
				->setVille($faker->randomElement($villes))
				->setTelephone('0' . $faker->randomElement(['6', '7']) . $faker->numerify('########'))
				->setAge($faker->numberBetween(18, 85))
				->setPoids($faker->numberBetween(45, 120))
				->setDureeMaladie($faker->numberBetween(1, 10))
				->setTypeMaladie($faker->randomElement(['Diabète', 'Hypertension', 'Ulcère', 'Anémie', 'Asthme']))
				->setTraitement($faker->sentence(6))
				->setObservation($faker->paragraph(2));

			// 📌 70% des clients ont des troubles digestifs
			if ($faker->boolean(70)) {

				$digestif = new TroublesDigestifs();
				$digestif->setAciditeGastrique($faker->randomElement(['Jamais', 'Parfois', 'Souvent']))
					->setConstipation($faker->randomElement(['Jamais', 'Parfois', 'Souvent']))
					->setDiarrhee($faker->randomElement(['Jamais', 'Parfois', 'Souvent']))
					->setAspectSelles($faker->randomElement(['Minces', 'Épaisses', 'Discontinues']))
					->setGaz($faker->randomElement(['Rarement', 'Parfois', 'Souvent']))
					->setEructation($faker->randomElement(['Rarement', 'Parfois', 'Souvent']))
					->setClient($client);

				$client->setTroublesDigestifs($digestif);
				$manager->persist($digestif);
			}

			$manager->persist($client);
		}

		$manager->flush();
	}
}
