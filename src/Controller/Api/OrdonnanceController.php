<?php

namespace App\Controller\Api;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Cabinet;
use App\Entity\SuiviSoin;
use App\Repository\CabinetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrdonnanceController extends AbstractController
{
	#[Route('/suivi/{id}/ordonnance', name: 'app_ordonnance_preview')]
	public function preview(
		Request $request,
		SuiviSoin $suivi,
		CabinetRepository $cabRepo
	): Response {
		$cabinet = $cabRepo->findOneBy([]);
		$patient = $suivi->getPatient();
		$medicaments = $suivi->getMedicine();

		// 🔹 mode impression ?
		if ($request->query->get('print')) {
			return $this->render('ordonnance/print.html.twig', [
				'cabinet' => $cabinet,
				'suivi' => $suivi,
				'patient' => $patient,
				'medicaments' => $medicaments
			]);
		}

		// 🔹 preview normal
		return $this->render('ordonnance/preview.html.twig', [
			'cabinet' => $cabinet,
			'suivi' => $suivi,
			'patient' => $patient,
			'medicaments' => $medicaments
		]);
	}

	#[Route('/api/ordonnance/{id}/pdf', name: 'api_ordonnance_pdf')]
	public function generate(SuiviSoin $suivi, CabinetRepository $cabRepo): Response
	{
		$cabinet = $cabRepo->findOneBy([]); // ✔️ fonctionne !

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
