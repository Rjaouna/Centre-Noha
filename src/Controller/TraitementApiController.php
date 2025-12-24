<?php // src/Controller/TraitementApiController.php
namespace App\Controller;

use App\Entity\Traitement;
use App\Repository\TraitementRepository;
use App\Repository\MedicineRepository;
use App\Repository\SymptomeRepository;
use App\Repository\MaladieChroniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/traitements')]
class TraitementApiController extends AbstractController
{
	#[Route('', methods: ['GET'])]
	public function list(TraitementRepository $repo): JsonResponse
	{
		$data = [];

		foreach ($repo->findAll() as $t) {
			$data[] = [
				'id' => $t->getId(),
				'symptome' => $t->getSymptome()->first()?->getName(),
				'medicine' => $t->getMedicine()->getName(),
				'description' => $t->getDescription(),
				'contreIndications' => array_map(
					fn($m) => $m->getNom(),
					$t->getContreIndications()->toArray()
				),
			];
		}

		return $this->json($data);
	}

	#[Route('', methods: ['POST'])]
	public function create(
		Request $request,
		EntityManagerInterface $em,
		MedicineRepository $medRepo,
		SymptomeRepository $symRepo,
		MaladieChroniqueRepository $mcRepo
	): JsonResponse {
		$data = json_decode($request->getContent(), true);

		$t = new Traitement();
		$t->setDescription($data['description']);
		$t->setMedicine($medRepo->find($data['medicine']));
		$t->addSymptome($symRepo->find($data['symptome']));

		foreach ($data['contreIndications'] ?? [] as $id) {
			$t->addContreIndication($mcRepo->find($id));
		}

		$em->persist($t);
		$em->flush();

		return $this->json(['success' => true]);
	}

	#[Route('/{id}', methods: ['GET'])]
	public function show(Traitement $t): JsonResponse
	{
		return $this->json([
			'id' => $t->getId(),
			'description' => $t->getDescription(),

			'medicine' => [
				'id' => $t->getMedicine()->getId(),
				'label' => $t->getMedicine()->getName() . ' ' . ($t->getMedicine()->getDosage() ?? '')
			],

			'symptome' => [
				'id' => $t->getSymptome()->first()?->getId(),
				'label' => $t->getSymptome()->first()?->getName()
			],

			'contreIndications' => array_map(
				fn($m) => $m->getId(),
				$t->getContreIndications()->toArray()
			),
		]);
	}


	#[Route('/{id}', methods: ['PUT'])]
	public function update(
		Traitement $t,
		Request $request,
		EntityManagerInterface $em,
		MedicineRepository $medRepo,
		SymptomeRepository $symRepo,
		MaladieChroniqueRepository $mcRepo
	): JsonResponse {
		$data = json_decode($request->getContent(), true);

		$t->setDescription($data['description']);
		$t->setMedicine($medRepo->find($data['medicine']));
		$t->getSymptome()->clear();
		$t->addSymptome($symRepo->find($data['symptome']));
		$t->getContreIndications()->clear();

		foreach ($data['contreIndications'] ?? [] as $id) {
			$t->addContreIndication($mcRepo->find($id));
		}

		$em->flush();
		return $this->json(['success' => true]);
	}

	#[Route('/{id}', methods: ['DELETE'])]
	public function delete(Traitement $t, EntityManagerInterface $em): JsonResponse
	{
		$em->remove($t);
		$em->flush();
		return $this->json(['success' => true]);
	}
}
