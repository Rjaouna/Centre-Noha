<?php

namespace App\Controller\Api;

use App\Entity\SuiviSoin;
use App\Entity\Cabinet;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdonnanceController extends AbstractController
{
	#[Route('/api/ordonnance/{id}/pdf', name: 'api_ordonnance_pdf')]
	public function generate(SuiviSoin $suivi): Response
	{
		$cabinet = $this->getDoctrine()->getRepository(Cabinet::class)->findOneBy([]);

		if (!$cabinet) {
			return new Response('Cabinet introuvable', 404);
		}

		$patient = $suivi->getPatient();
		$medicaments = $suivi->getMedicine();

		// Options PDF
		$options = new Options();
		$options->set('defaultFont', 'Helvetica');
		$options->setIsRemoteEnabled(true);

		$dompdf = new Dompdf($options);

		// HTML rendu pour le PDF
		$html = $this->renderView('ordonnance/ordonnance_pdf.html.twig', [
			'cabinet' => $cabinet,
			'suivi' => $suivi,
			'patient' => $patient,
			'medicaments' => $medicaments
		]);

		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		return new Response(
			$dompdf->output(),
			200,
			[
				'Content-Type' => 'application/pdf',
				'Content-Disposition' => "attachment; filename=ordonnance-{$suivi->getId()}.pdf"
			]
		);
	}
}
