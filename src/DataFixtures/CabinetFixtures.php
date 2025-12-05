<?php

namespace App\DataFixtures;

use App\Entity\Cabinet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CabinetFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$cabinet = new Cabinet();
		$cabinet->setNom('SoinBoard');
		$cabinet->setType('Centre de soins');
		$cabinet->setAdresse('12 Rue des Jasmins');
		$cabinet->setVille('Casablanca');
		$cabinet->setTelephone('0695854754');
		$cabinet->setEmail('contact@soinboard.ma');

		$manager->persist($cabinet);
		$manager->flush();
	}
}
