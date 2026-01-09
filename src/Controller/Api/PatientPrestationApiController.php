<?php

namespace App\Controller\Api;

use App\Entity\PatientPrestation;
use App\Repository\FicheClientRepository;
use App\Repository\PatientPrestationRepository;
use App\Repository\PrestationPriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PatientPrestationApiController extends AbstractController
{
	#[Route('/api/patients/{id}/prestations', name: 'api_patient_prestations_create', methods: ['POST'])]
	public function create(
		int $id,
		Request $request,
		FicheClientRepository $patientRepo,
		PrestationPriceRepository $prestationRepo,
		EntityManagerInterface $em
	): JsonResponse {
		$patient = $patientRepo->find($id);
		if (!$patient) {
			return $this->json(['success' => false, 'message' => 'Patient introuvable'], 404);
		}

		$payload = json_decode($request->getContent(), true);
		if (!is_array($payload)) {
			return $this->json(['success' => false, 'message' => 'JSON invalide'], 400);
		}

		$items = $payload['items'] ?? null;
		if (!is_array($items) || count($items) === 0) {
			return $this->json(['success' => false, 'message' => 'Aucune prestation sélectionnée'], 400);
		}

		$created = 0;
		$errors = [];

		foreach ($items as $idx => $it) {
			$prestationId = (int)($it['prestationId'] ?? 0);
			$quantite = (int)($it['quantite'] ?? 1);

			if ($prestationId <= 0) {
				$errors[] = "Ligne #$idx: prestationId manquant";
				continue;
			}

			if ($quantite < 1) {
				$errors[] = "Ligne #$idx: quantité invalide";
				continue;
			}

			$prestation = $prestationRepo->find($prestationId);
			if (!$prestation) {
				$errors[] = "Ligne #$idx: prestation introuvable ($prestationId)";
				continue;
			}

			// ✅ Snapshot du prix au moment (prix est DECIMAL string)
			$prixUnitaire = (float) str_replace(',', '.', (string) $prestation->getPrix());

			$pp = new PatientPrestation();
			$pp->setPatient($patient);
			$pp->setPrestation($prestation);
			$pp->setQuantite($quantite);
			$pp->setPrixUnitaire($prixUnitaire);

			$em->persist($pp);
			$created++;
		}

		if ($created === 0) {
			return $this->json([
				'success' => false,
				'message' => 'Aucune ligne valide à enregistrer',
				'errors' => $errors
			], 400);
		}

		$em->flush();

		return $this->json([
			'success' => true,
			'message' => "Prestations ajoutées ($created)",
			'created' => $created,
			'errors' => $errors
		]);
	}

	#[Route('/api/patients/{id}/prestations', name: 'api_patient_prestations_list', methods: ['GET'])]
	public function list(
		int $id,
		FicheClientRepository $patientRepo,
		PatientPrestationRepository $repo
	): JsonResponse {
		$patient = $patientRepo->find($id);
		if (!$patient) {
			return $this->json(['success' => false, 'message' => 'Patient introuvable'], 404);
		}

		$rows = $repo->findBy(['patient' => $patient], ['createdAt' => 'DESC']);

		$data = array_map(static function (PatientPrestation $pp) {
			$p = $pp->getPrestation();

			return [
				'id' => $pp->getId(),
				'prestationId' => $p?->getId(),
				'nom' => $p?->getNom(),
				'categorie' => $p?->getCategorie(),
				'quantite' => $pp->getQuantite(),
				'prixUnitaire' => $pp->getPrixUnitaire(),
				'total' => $pp->getTotal(),
				'createdAt' => $pp->getCreatedAt()->format('Y-m-d H:i:s'),
			];
		}, $rows);

		return $this->json(['success' => true, 'data' => $data]);
	}

	#[Route('/api/patient-prestations/{id}', name: 'api_patient_prestations_delete', methods: ['DELETE'])]
	public function delete(
		int $id,
		PatientPrestationRepository $repo,
		EntityManagerInterface $em
	): JsonResponse {
		$pp = $repo->find($id);
		if (!$pp) {
			return $this->json(['success' => false, 'message' => 'Ligne introuvable'], 404);
		}

		$em->remove($pp);
		$em->flush();

		return $this->json(['success' => true, 'message' => 'Supprimé']);
	}
}
