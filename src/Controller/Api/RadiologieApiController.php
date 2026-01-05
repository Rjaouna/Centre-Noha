<?php

namespace App\Controller\Api;

use App\Entity\FicheClient;
use App\Entity\Radiologie;
use App\Repository\RadioTypeRepository;
use App\Repository\RadiologieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\RadioType;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api')]
class RadiologieApiController extends AbstractController
{
	#[Route('/radios-types/search', name: 'api_radio_type_search', methods: ['GET'])]
public function search(Request $request, RadioTypeRepository $repo): JsonResponse
{
    $q = trim((string)$request->query->get('q', ''));
    if (mb_strlen($q) < 2) return $this->json([]);

    // simple LIKE (tu peux passer en QueryBuilder si tu veux plus propre)
    $all = $repo->findBy([], ['nom' => 'ASC']);

    $filtered = array_filter($all, function(RadioType $t) use ($q) {
        $hay = mb_strtolower($t->getNom().' '.$t->getZoneCorps().' '.($t->getDescription() ?? ''));
        return str_contains($hay, mb_strtolower($q));
    });

    $filtered = array_slice(array_values($filtered), 0, 15);

    return $this->json(array_map(fn(RadioType $t) => [
        'id' => $t->getId(),
        'nom' => $t->getNom(),
        'zone_corps' => $t->getZoneCorps(),
        'description' => $t->getDescription(),
    ], $filtered));
}

	#[Route('/patients/{id}/radiologies', name: 'api_patient_radiologies_list', methods: ['GET'])]
	public function listForPatient(FicheClient $patient, RadiologieRepository $repo): JsonResponse
	{
		$items = $repo->findBy(['patient' => $patient], ['prescriptionAt' => 'DESC']);

		return $this->json(array_map(function (Radiologie $r) {
			return [
				'id' => $r->getId(),
				'motif' => $r->getMotif(),
				'statut' => $r->getStatut(),
				'compteRendu' => $r->getCompteRendu(),
				'fichier' => $r->getFichier(), // chemin relatif (ex: uploads/radiologies/xxx.pdf)
				'prescriptionAt' => $r->getPrescriptionAt()?->format('Y-m-d H:i'),
				'types' => $r->getRadioType()->map(fn($t) => [
					'id' => $t->getId(),
					'nom' => $t->getNom(),
					'zone' => $t->getZoneCorps(),
				])->toArray()
			];
		}, $items));
	}

	#[Route('/patients/{id}/radiologies', name: 'api_patient_radiologies_create', methods: ['POST'])]
	public function createForPatient(
		FicheClient $patient,
		Request $request,
		EntityManagerInterface $em,
		RadioTypeRepository $typeRepo
	): JsonResponse {
		$data = json_decode($request->getContent(), true);

		$motif = trim((string)($data['motif'] ?? ''));
		$typeIds = $data['typeIds'] ?? [];

		if ($motif === '') {
			return $this->json(['success' => false, 'message' => 'Le motif est obligatoire.'], 400);
		}
		if (!is_array($typeIds) || count($typeIds) === 0) {
			return $this->json(['success' => false, 'message' => 'Veuillez sélectionner au moins un type de radiologie.'], 400);
		}

		$radio = new Radiologie();
		$radio->setPatient($patient);
		$radio->setMotif($motif);
		$radio->setStatut('prescrite');
		$radio->setPrescriptionAt(new \DateTime());

		foreach ($typeIds as $id) {
			$t = $typeRepo->find((int)$id);
			if ($t) {
				$radio->addRadioType($t);
			}
		}

		if ($radio->getRadioType()->count() === 0) {
			return $this->json(['success' => false, 'message' => 'Types invalides.'], 400);
		}

		$em->persist($radio);
		$em->flush();

		return $this->json(['success' => true, 'id' => $radio->getId()]);
	}

	#[Route('/radiologies/{id}/resultat', name: 'api_radiologie_resultat', methods: ['POST'])]
	public function uploadResultat(
		Radiologie $radiologie,
		Request $request,
		EntityManagerInterface $em,
		SluggerInterface $slugger
	): JsonResponse {

		// 1️⃣ Compte rendu obligatoire
		$compteRendu = trim((string)$request->request->get('compteRendu', ''));
		if ($compteRendu === '') {
			return $this->json(['success' => false, 'message' => 'Le compte-rendu est obligatoire.'], 400);
		}
		$radiologie->setCompteRendu($compteRendu);

		// 2️⃣ SUPPRESSION DU FICHIER EXISTANT (ICI 👇)
		$removeFile = $request->request->get('removeFile') === '1';

		if ($removeFile && $radiologie->getFichier()) {
			@unlink(
				$this->getParameter('kernel.project_dir') . '/public/' . $radiologie->getFichier()
			);
			$radiologie->setFichier(null);
		}

		// 3️⃣ UPLOAD NOUVEAU FICHIER (OPTIONNEL)
		$file = $request->files->get('fichier');
		if ($file) {
			$original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
			$safeName = $slugger->slug($original)->lower();
			$newName = $safeName . '-' . uniqid() . '.' . $file->guessExtension();

			$targetDir = $this->getParameter('kernel.project_dir') . '/public/uploads/radiologies';

			try {
				$file->move($targetDir, $newName);
			} catch (FileException $e) {
				return $this->json(['success' => false, 'message' => 'Upload impossible.'], 500);
			}

			$radiologie->setFichier('uploads/radiologies/' . $newName);
			$radiologie->setStatut('realisee');
		}

		// 4️⃣ SAVE
		$em->flush();

		return $this->json([
			'success' => true,
			'id' => $radiologie->getId(),
			'statut' => $radiologie->getStatut(),
			'compteRendu' => $radiologie->getCompteRendu(),
			'fichier' => $radiologie->getFichier(),
		]);
	}
}
