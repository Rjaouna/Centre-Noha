<?php

namespace App\Controller\Api;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Cabinet;
use App\Entity\Hopital;
use App\Entity\Patient;
use App\Entity\FicheClient;
use App\Repository\CabinetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecommendationController extends AbstractController
{

	#[Route('/recommendation/new', name: 'recommendation_new')]
	public function index(Request $request, EntityManagerInterface $em, CabinetRepository $cabinetRepository): Response
	{
		$cabinet = $cabinetRepository->findOneBy([]);
		$patientId = $request->query->get('patient');
		$hopitalId = $request->query->get('hopital');
		$motif = $request->query->get('motif');

		if (!$patientId || !$hopitalId) {
			return new Response("Paramètres manquants", 400);
		}

		$patient = $em->getRepository(FicheClient::class)->find($patientId);
		$hopital = $em->getRepository(Hopital::class)->find($hopitalId);

		if (!$patient || !$hopital) {
			return new Response("Patient ou hôpital introuvable", 404);
		}

		// 👉 Maintenant, tu peux créer ton PDF ici
		// Exemple : return new PdfResponse(...)

		return $this->render('recommendation/preview.html.twig', [
			'patient' => $patient,
			'hopital' => $hopital,
			'cabinet' => $cabinet,
			'motif' => $motif
		]);
	}

	#[Route('/recommendation/pdf/{patientId}/{hopitalId}', name: 'app_recommendation_pdf')]
	public function generate(
		Request $request,
		int $patientId,
		int $hopitalId,
		EntityManagerInterface $em
	): Response {

		// Récupération des données
		$patient = $em->getRepository(FicheClient::class)->find($patientId);
		$hopital = $em->getRepository(Hopital::class)->find($hopitalId);
		$cabinet = $em->getRepository(Cabinet::class)->findOneBy([]);
		$motif = $request->query->get('motif');

		if (!$patient || !$hopital || !$cabinet) {
			return new Response("Données introuvables", 404);
		}

		// Génération HTML → PDF
		$html = $this->renderView('recommendation/pdf_template.html.twig', [
			'patient' => $patient,
			'hopital' => $hopital,
			'cabinet' => $cabinet,
			'user' => $this->getUser(),
			'motif' => $motif
		]);

		$options = new Options();
		$options->set('defaultFont', 'Helvetica');
		$options->setIsRemoteEnabled(true);

		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		return new Response(
			$dompdf->stream("lettre_recommandation.pdf", ["Attachment" => true])
		);
	}
}
