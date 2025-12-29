<?php

namespace App\Controller;

use App\Entity\FicheClient;
use App\Entity\ArretMaladie;
use App\Repository\CabinetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/arret-maladie')]
class ArretMaladieController extends AbstractController
{
	#[Route('/save/{patient}', name: 'arret_maladie_save', methods: ['POST'])]
	public function save(
		FicheClient $patient,
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {

		$motif = $request->request->get('motif');
		$finAt = $request->request->get('finAt');

		if (!$motif || !$finAt) {
			return new JsonResponse(['success' => false], 400);
		}

		$debutAt = new \DateTimeImmutable(); // aujourd’hui
		$finAt   = new \DateTimeImmutable($finAt);

		// 🔢 Calcul durée
		$duree = $debutAt->diff($finAt)->days + 1;

		// 📝 Texte par défaut (template)
		$body = sprintf(
			"Au vu de l’examen clinique réalisé ce jour, l’état de santé du patient nécessite un arrêt de travail d’une durée de %d jour(s), à compter du %s inclus.

Cet arrêt est prescrit pour le motif suivant : %s.

Il est recommandé au patient de respecter un repos adapté durant toute la durée de l’arrêt et d’éviter toute activité professionnelle susceptible de nuire à son rétablissement.

Une reprise du travail pourra être envisagée à l’issue de cette période, sous réserve d’une évolution favorable.",
			$duree,
			$debutAt->format('d/m/Y'),
			$motif
		);

		$arret = new ArretMaladie();
		$arret
			->setPatient($patient)
			->setMotif($motif)
			->setDebutAt($debutAt)
			->setFinAt($finAt)
			->setDuree($duree)
			->setBody($body);

		$em->persist($arret);
		$em->flush();

		return new JsonResponse([
			'success' => true,
			'id' => $arret->getId()
		]);
	}

	#[Route('/print/{id}', name: 'arret_maladie_print', methods: ['GET'])]
	public function print(ArretMaladie $arret, CabinetRepository $cabinetRepository): Response
	{
		return $this->render('arret_maladie/print.html.twig', [
			'cabinet' => $cabinetRepository->findOneBy([]),
			'arret'   => $arret,
			'patient' => $arret->getPatient(),
			'medecin' => $this->getUser(),
		]);
	}
}
