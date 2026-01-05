<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\VilleMaroc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VilleMarocFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$villes = [

			// 🔹 Casablanca-Settat
			['nom' => 'Casablanca', 'region' => 'Casablanca-Settat'],
			['nom' => 'Mohammedia', 'region' => 'Casablanca-Settat'],
			['nom' => 'Settat', 'region' => 'Casablanca-Settat'],
			['nom' => 'El Jadida', 'region' => 'Casablanca-Settat'],
			['nom' => 'Azemmour', 'region' => 'Casablanca-Settat'],
			['nom' => 'Sidi Bennour', 'region' => 'Casablanca-Settat'],
			['nom' => 'Berrechid', 'region' => 'Casablanca-Settat'],
			['nom' => 'Bouskoura', 'region' => 'Casablanca-Settat'],

			// 🔹 Rabat-Salé-Kénitra
			['nom' => 'Rabat', 'region' => 'Rabat-Salé-Kénitra'],
			['nom' => 'Salé', 'region' => 'Rabat-Salé-Kénitra'],
			['nom' => 'Kénitra', 'region' => 'Rabat-Salé-Kénitra'],
			['nom' => 'Skhirate', 'region' => 'Rabat-Salé-Kénitra'],
			['nom' => 'Témara', 'region' => 'Rabat-Salé-Kénitra'],
			['nom' => 'Sidi Kacem', 'region' => 'Rabat-Salé-Kénitra'],
			['nom' => 'Sidi Slimane', 'region' => 'Rabat-Salé-Kénitra'],

			// 🔹 Fès-Meknès
			['nom' => 'Fès', 'region' => 'Fès-Meknès'],
			['nom' => 'Meknès', 'region' => 'Fès-Meknès'],
			['nom' => 'Sefrou', 'region' => 'Fès-Meknès'],
			['nom' => 'El Hajeb', 'region' => 'Fès-Meknès'],
			['nom' => 'Ifrane', 'region' => 'Fès-Meknès'],
			['nom' => 'Boulemane', 'region' => 'Fès-Meknès'],
			['nom' => 'Taza', 'region' => 'Fès-Meknès'],

			// 🔹 Marrakech-Safi
			['nom' => 'Marrakech', 'region' => 'Marrakech-Safi'],
			['nom' => 'Safi', 'region' => 'Marrakech-Safi'],
			['nom' => 'Essaouira', 'region' => 'Marrakech-Safi'],
			['nom' => 'El Kelaâ des Sraghna', 'region' => 'Marrakech-Safi'],
			['nom' => 'Chichaoua', 'region' => 'Marrakech-Safi'],
			['nom' => 'Youssoufia', 'region' => 'Marrakech-Safi'],

			// 🔹 Tanger-Tétouan-Al Hoceïma
			['nom' => 'Tanger', 'region' => 'Tanger-Tétouan-Al Hoceïma'],
			['nom' => 'Tétouan', 'region' => 'Tanger-Tétouan-Al Hoceïma'],
			['nom' => 'Al Hoceïma', 'region' => 'Tanger-Tétouan-Al Hoceïma'],
			['nom' => 'Larache', 'region' => 'Tanger-Tétouan-Al Hoceïma'],
			['nom' => 'Chefchaouen', 'region' => 'Tanger-Tétouan-Al Hoceïma'],
			['nom' => 'Fnideq', 'region' => 'Tanger-Tétouan-Al Hoceïma'],
			['nom' => 'Martil', 'region' => 'Tanger-Tétouan-Al Hoceïma'],

			// 🔹 Souss-Massa
			['nom' => 'Agadir', 'region' => 'Souss-Massa'],
			['nom' => 'Inezgane', 'region' => 'Souss-Massa'],
			['nom' => 'Aït Melloul', 'region' => 'Souss-Massa'],
			['nom' => 'Taroudant', 'region' => 'Souss-Massa'],
			['nom' => 'Tiznit', 'region' => 'Souss-Massa'],

			// 🔹 Oriental
			['nom' => 'Oujda', 'region' => 'Oriental'],
			['nom' => 'Nador', 'region' => 'Oriental'],
			['nom' => 'Berkane', 'region' => 'Oriental'],
			['nom' => 'Taourirt', 'region' => 'Oriental'],
			['nom' => 'Jerada', 'region' => 'Oriental'],
			['nom' => 'Guercif', 'region' => 'Oriental'],

			// 🔹 Drâa-Tafilalet
			['nom' => 'Errachidia', 'region' => 'Drâa-Tafilalet'],
			['nom' => 'Ouarzazate', 'region' => 'Drâa-Tafilalet'],
			['nom' => 'Zagora', 'region' => 'Drâa-Tafilalet'],
			['nom' => 'Tinghir', 'region' => 'Drâa-Tafilalet'],
			['nom' => 'Midelt', 'region' => 'Drâa-Tafilalet'],

			// 🔹 Béni Mellal-Khénifra
			['nom' => 'Béni Mellal', 'region' => 'Béni Mellal-Khénifra'],
			['nom' => 'Khénifra', 'region' => 'Béni Mellal-Khénifra'],
			['nom' => 'Azilal', 'region' => 'Béni Mellal-Khénifra'],
			['nom' => 'Fquih Ben Salah', 'region' => 'Béni Mellal-Khénifra'],

			// 🔹 Guelmim-Oued Noun
			['nom' => 'Guelmim', 'region' => 'Guelmim-Oued Noun'],
			['nom' => 'Sidi Ifni', 'region' => 'Guelmim-Oued Noun'],
			['nom' => 'Tan-Tan', 'region' => 'Guelmim-Oued Noun'],

			// 🔹 Laâyoune-Sakia El Hamra
			['nom' => 'Laâyoune', 'region' => 'Laâyoune-Sakia El Hamra'],
			['nom' => 'Boujdour', 'region' => 'Laâyoune-Sakia El Hamra'],
			['nom' => 'Tarfaya', 'region' => 'Laâyoune-Sakia El Hamra'],
			['nom' => 'Smara', 'region' => 'Laâyoune-Sakia El Hamra'],

			// 🔹 Dakhla-Oued Ed-Dahab
			['nom' => 'Dakhla', 'region' => 'Dakhla-Oued Ed-Dahab'],
			['nom' => 'Aousserd', 'region' => 'Dakhla-Oued Ed-Dahab'],
		];

		foreach ($villes as $data) {
			$ville = new VilleMaroc();
			$ville->setNom($data['nom']);
			$ville->setRegion($data['region']);

			$manager->persist($ville);
		}

		$manager->flush();
	}
}
