<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
	private UserPasswordHasherInterface $hasher;

	public function __construct(UserPasswordHasherInterface $hasher)
	{
		$this->hasher = $hasher;
	}

	public function load(ObjectManager $manager): void
	{
		// 🔥 SUPER ADMIN
		$superAdmin = new User();
		$superAdmin->setEmail('superadmin@noha.ma');
		$superAdmin->setRoles(['ROLE_SUPER_ADMIN']);
		$password = $this->hasher->hashPassword($superAdmin, 'superadmin123');
		$superAdmin->setPassword($password);
		$manager->persist($superAdmin);

		// 🔵 ADMIN
		$admin = new User();
		$admin->setEmail('admin@noha.ma');
		$admin->setRoles(['ROLE_ADMIN']);
		$password = $this->hasher->hashPassword($admin, 'admin123');
		$admin->setPassword($password);
		$manager->persist($admin);

		// 🟢 ASSISTANCE
		$assist = new User();
		$assist->setEmail('assist@noha.ma');
		$assist->setRoles(['ROLE_ASSIST']);
		$password = $this->hasher->hashPassword($assist, 'assist123');
		$assist->setPassword($password);
		$manager->persist($assist);

		// 🟢 Medecin
		$assist = new User();
		$assist->setEmail('dr.brouk@gmail.com');
		$assist->setRoles(['ROLE_ASSIST']);
		$password = $this->hasher->hashPassword($assist, 'brouk2025');
		$assist->setPassword($password);
		$manager->persist($assist);

		// 💾 Sauvegarde en BDD
		$manager->flush();
	}
}
