<?php

namespace App\Service;

use App\Entity\FicheClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InlineFicheClientUpdater
{
	private const ALLOWED_FIELDS = [
		'nom',
		'ville',
		'telephone',
		'poids',
		'dureeMaladie',
		'typeMaladie',
		'traitement',
		'observation',
	];

	public function __construct(
		private EntityManagerInterface $em
	) {}

	public function update(
		FicheClient $client,
		string $field,
		?string $value
	): string {
		if (!in_array($field, self::ALLOWED_FIELDS, true)) {
			throw new BadRequestHttpException('Champ non autorisé');
		}

		$setter = 'set' . ucfirst($field);

		if (!method_exists($client, $setter)) {
			throw new BadRequestHttpException('Méthode inexistante');
		}

		// Nettoyage de base
		$value = trim((string) $value);
		$value = $value === '' ? null : $value;

		// Validation simple par champ
		match ($field) {
			'telephone' => $this->validateTelephone($value),
			'poids' => $this->validatePoids($value),
			'dureeMaladie' => $this->validateDuree($value),
			default => null,
		};

		$client->$setter($value);
		$this->em->flush();

		return $value ?? '—';
	}

	private function validateTelephone(?string $value): void
	{
		if ($value && !preg_match('/^[0-9]{10}$/', $value)) {
			throw new BadRequestHttpException('Téléphone invalide');
		}
	}

	private function validatePoids(?string $value): void
	{
		if ($value && !preg_match('/^\d{1,3}(\.\d{1})?$/', $value)) {
			throw new BadRequestHttpException('Poids invalide');
		}
	}

	private function validateDuree(?string $value): void
	{
		if ($value && !preg_match('/^\d+$/', $value)) {
			throw new BadRequestHttpException('Durée invalide');
		}
	}
}
