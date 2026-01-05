<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\FicheClient;
use App\Repository\FicheClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/fiche-clients', name: 'api_fiche_clients_')]
class FicheClientApiController extends AbstractController
{
	public function __construct(
		private readonly EntityManagerInterface $em,
		private readonly FicheClientRepository $repo,
		private readonly SerializerInterface $serializer,
		private readonly ValidatorInterface $validator,
	) {}

	// -------------------------
	// GET /api/fiche-clients
	// -------------------------
	#[Route('', name: 'index', methods: ['GET'])]
	public function index(Request $request): JsonResponse
	{
		$limit = max(1, (int) $request->query->get('limit', 25));
		$page  = max(1, (int) $request->query->get('page', 1));
		$offset = ($page - 1) * $limit;

		// Optionnel: recherche simple
		$q = trim((string) $request->query->get('q', ''));

		if ($q !== '') {
			// Si tu as une méthode repo dédiée, remplace ici.
			$items = $this->repo->createQueryBuilder('c')
				->andWhere('c.nom LIKE :q OR c.prenom LIKE :q OR c.telephone LIKE :q')
				->setParameter('q', '%' . $q . '%')
				->setFirstResult($offset)
				->setMaxResults($limit)
				->orderBy('c.id', 'DESC')
				->getQuery()
				->getResult();
		} else {
			$items = $this->repo->findBy([], ['id' => 'DESC'], $limit, $offset);
		}

		$json = $this->serializer->serialize($items, 'json', [
			'groups' => $this->getReadGroups($request),
		]);

		return new JsonResponse($json, 200, [], true);
	}

	// -------------------------
	// GET /api/fiche-clients/{id}
	// -------------------------
	#[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
	public function show(int $id, Request $request): JsonResponse
	{
		$client = $this->repo->find($id);
		if (!$client) {
			return $this->json(['message' => 'Patient introuvable.'], 404);
		}

		$json = $this->serializer->serialize($client, 'json', [
			'groups' => $this->getReadGroups($request),
		]);

		return new JsonResponse($json, 200, [], true);
	}

	// -------------------------
	// POST /api/fiche-clients
	// -------------------------
	#[Route('', name: 'create', methods: ['POST'])]
	public function create(Request $request): JsonResponse
	{
		$data = $this->decodeJson($request);
		if ($data === null) {
			return $this->json(['message' => 'JSON invalide.'], 400);
		}

		$client = new FicheClient();

		// Appliquer payload (création)
		$this->applyPayload($client, $data, isPatch: false);

		// Defaults utiles
		if ($client->isOpen() === null) {
			$client->setIsOpen(true);
		}

		$errors = $this->validator->validate($client);
		if (count($errors) > 0) {
			return $this->json(['errors' => $this->formatValidationErrors($errors)], 422);
		}
		$required = ['nom', 'prenom', 'ville', 'telephone', 'typeMaladie'];
		$errs = [];

		foreach ($required as $f) {
			$val = match ($f) {
				'nom' => $client->getNom(),
				'prenom' => $client->getPrenom(),
				'ville' => $client->getVille(),
				'telephone' => $client->getTelephone(),
				'typeMaladie' => $client->getTypeMaladie(),
				default => null,
			};

			if ($val === null || trim((string)$val) === '') {
				$errs[$f][] = 'Champ obligatoire.';
			}
		}

		if (!empty($errs)) {
			return $this->json(['errors' => $errs], 422);
		}


		$this->em->persist($client);
		$this->em->flush();

		$json = $this->serializer->serialize($client, 'json', [
			'groups' => $this->getReadGroups($request),
		]);

		return new JsonResponse($json, 201, [], true);
	}

	// -------------------------
	// PUT /api/fiche-clients/{id}
	// (update complet)
	// -------------------------
	#[Route('/{id<\d+>}', name: 'update', methods: ['PUT'])]
	public function update(int $id, Request $request): JsonResponse
	{
		$client = $this->repo->find($id);
		if (!$client) {
			return $this->json(['message' => 'Patient introuvable.'], 404);
		}

		$data = $this->decodeJson($request);
		if ($data === null) {
			return $this->json(['message' => 'JSON invalide.'], 400);
		}

		// PUT = on considère update complet
		$this->applyPayload($client, $data, isPatch: false);

		$errors = $this->validator->validate($client);
		if (count($errors) > 0) {
			return $this->json(['errors' => $this->formatValidationErrors($errors)], 422);
		}

		$this->em->flush();

		$json = $this->serializer->serialize($client, 'json', [
			'groups' => $this->getReadGroups($request),
		]);

		return new JsonResponse($json, 200, [], true);
	}

	// -------------------------
	// PATCH /api/fiche-clients/{id}
	// (update partiel)
	// -------------------------
	#[Route('/{id<\d+>}', name: 'patch', methods: ['PATCH'])]
	public function patch(int $id, Request $request): JsonResponse
	{
		$client = $this->repo->find($id);
		if (!$client) {
			return $this->json(['message' => 'Patient introuvable.'], 404);
		}

		$data = $this->decodeJson($request);
		if ($data === null) {
			return $this->json(['message' => 'JSON invalide.'], 400);
		}

		$this->applyPayload($client, $data, isPatch: true);

		$errors = $this->validator->validate($client);
		if (count($errors) > 0) {
			return $this->json(['errors' => $this->formatValidationErrors($errors)], 422);
		}

		$this->em->flush();

		$json = $this->serializer->serialize($client, 'json', [
			'groups' => $this->getReadGroups($request),
		]);

		return new JsonResponse($json, 200, [], true);
	}

	// -------------------------
	// DELETE /api/fiche-clients/{id}
	// -------------------------
	#[Route('/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
	public function delete(int $id): JsonResponse
	{
		$client = $this->repo->find($id);
		if (!$client) {
			return $this->json(['message' => 'Patient introuvable.'], 404);
		}

		$this->em->remove($client);
		$this->em->flush();

		return $this->json(['message' => 'Patient supprimé.'], 200);
	}

	// ==========================================================
	// Helpers
	// ==========================================================

	private function decodeJson(Request $request): ?array
	{
		$content = $request->getContent();
		if ($content === '') {
			return [];
		}

		try {
			$data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
		} catch (\Throwable) {
			return null;
		}

		return is_array($data) ? $data : null;
	}

	/**
	 * Applique le payload JSON dans l'entité.
	 * - PATCH: n'applique que les champs présents
	 * - PUT/POST: applique tout ce qui est présent, et tu peux forcer des champs requis si tu veux
	 */
	private function applyPayload(FicheClient $client, array $data, bool $isPatch): void
	{
		// strings
		if (!$isPatch || array_key_exists('nom', $data)) {
			$client->setNom($data['nom'] ?? null);
		}

		if (!$isPatch || array_key_exists('prenom', $data)) {
			$client->setPrenom($data['prenom'] ?? '');
		}

		if (!$isPatch || array_key_exists('ville', $data)) {
			$client->setVille($data['ville'] ?? null);
		}

		if (!$isPatch || array_key_exists('telephone', $data)) {
			$client->setTelephone($data['telephone'] ?? null);
		}

		if (!$isPatch || array_key_exists('cin', $data)) {
			$client->setCin($data['cin'] ?? null);
		}

		if (!$isPatch || array_key_exists('poids', $data)) {
			$client->setPoids($data['poids'] ?? null);
		}

		if (!$isPatch || array_key_exists('dureeMaladie', $data)) {
			$client->setDureeMaladie($data['dureeMaladie'] ?? null);
		}

		if (!$isPatch || array_key_exists('typeMaladie', $data)) {
			$client->setTypeMaladie($data['typeMaladie'] ?? null);
		}

		if (!$isPatch || array_key_exists('traitement', $data)) {
			$client->setTraitement($data['traitement'] ?? null);
		}

		if (!$isPatch || array_key_exists('observation', $data)) {
			$client->setObservation($data['observation'] ?? null);
		}

		// bools
		if (!$isPatch || array_key_exists('isOpen', $data)) {
			if (array_key_exists('isOpen', $data)) {
				$client->setIsOpen((bool) $data['isOpen']);
			}
		}

		if (!$isPatch || array_key_exists('isConsulted', $data)) {
			if (array_key_exists('isConsulted', $data)) {
				$client->setIsConsulted($data['isConsulted'] === null ? null : (bool) $data['isConsulted']);
			}
		}

		// ⭐ Âge -> calcule dateNaissance (DateTimeImmutable) (+ setAge() pour compat si tu veux)
		if (array_key_exists('ageYears', $data)) {
			$ageYears = $data['ageYears'];

			if ($ageYears === null || $ageYears === '') {
				// si tu veux autoriser null en PATCH, laisse comme ça
				return;
			}

			$ageInt = (int) $ageYears;
			if ($ageInt < 0) $ageInt = 0;
			if ($ageInt > 120) $ageInt = 120;

			$dob = (new \DateTimeImmutable('today'))->modify(sprintf('-%d years', $ageInt));

			// ton entité a dateNaissance (DateTimeImmutable)
			$client->setDateNaissance($dob);

			// ton entité a aussi "age" (date) : on le remplit aussi pour rester cohérent
			if (method_exists($client, 'setAge')) {
				$client->setAge($dob);
			}
		}
	}

	private function getReadGroups(Request $request): array
	{
		// Exemple: /api/fiche-clients?view=suivi
		$view = (string) $request->query->get('view', 'admission');

		return match ($view) {
			'suivi' => ['suivi_read', 'admission_read'],
			'rdv'   => ['rdv:read', 'admission_read'],
			default => ['admission_read'],
		};
	}

	private function formatValidationErrors(\Traversable $errors): array
	{
		$out = [];
		foreach ($errors as $error) {
			$property = $error->getPropertyPath() ?: '_global';
			$out[$property][] = $error->getMessage();
		}
		return $out;
	}
}
