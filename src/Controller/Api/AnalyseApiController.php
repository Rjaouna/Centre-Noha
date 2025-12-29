<?php

namespace App\Controller\Api;

use App\Entity\Analyse;
use App\Entity\FicheClient;
use App\Entity\AnalysePrescription;
use App\Repository\AnalyseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class AnalyseApiController extends AbstractController
{
	#[Route('/analyse/search', name: 'analyse_search', methods: ['GET'])]
	public function search(Request $request, AnalyseRepository $repo): JsonResponse
	{
		$q = trim((string) $request->query->get('q', ''));

		if (mb_strlen($q) < 2) {
			return $this->json([]);
		}

		$data = $repo->createQueryBuilder('a')
			->select('a.id, a.name')
			->where('a.name LIKE :q')
			->setParameter('q', '%' . $q . '%')
			->setMaxResults(10)
			->getQuery()
			->getArrayResult();

		return $this->json($data);
	}

	#[Route('/analyse/prescription', name: 'analyse_prescription_create', methods: ['POST'])]
	public function savePrescription(Request $request, EntityManagerInterface $em): JsonResponse
	{
		$data = json_decode($request->getContent(), true);

		if (!$data || empty($data['patient']) || empty($data['analyses'])) {
			return $this->json(['success' => false, 'message' => 'Données invalides'], 422);
		}

		$prescription = new AnalysePrescription();
		$prescription->setPatient($em->getReference(FicheClient::class, (int)$data['patient']));
		$prescription->setAnalyses($data['analyses']);
		$prescription->setComment($data['comment'] ?? null);
		$prescription->setStatus('prescrite');
		$prescription->setPrescribedAt(new \DateTimeImmutable());

		$em->persist($prescription);
		$em->flush();

		return $this->json(['success' => true, 'id' => $prescription->getId()]);
	}
}
