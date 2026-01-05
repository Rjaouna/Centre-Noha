<?php

namespace App\Controller;

use App\Entity\Radiologie;
use App\Repository\CabinetRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Dompdf\Dompdf;
use Dompdf\Options;

class RadiologieController extends AbstractController
{
	#[Route('/radiologie/prescription/{id}', name: 'radiologie_prescription_show', methods: ['GET'])]
	public function prescription(Radiologie $radiologie, CabinetRepository $cabinetRepository): Response
	{
		$cabinet = $cabinetRepository->findOneBy([]);
		return $this->render('radiologie/prescription.html.twig', [
			'radiologie' => $radiologie,
			'patient' => $radiologie->getPatient(),
			'types' => $radiologie->getRadioType(),
			'cabinet' => $cabinet,
			
		]);
	}
	#[Route('/radiologie/{id}/pdf', name: 'radiologie_prescription_pdf')]
	public function pdf(Radiologie $radiologie, CabinetRepository $cabinetRepository): Response
	{
		$options = new Options();
		$options->set('defaultFont', 'Helvetica');
		$options->set('isRemoteEnabled', true);

		$dompdf = new Dompdf($options);

		$html = $this->renderView('radiologie/pdf_prescription.html.twig', [
			'radiologie' => $radiologie,
			'patient' => $radiologie->getPatient(),
			'cabinet' => $cabinetRepository->findOneBy([]),
			'user' => $this->getUser(),
		]);

		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4');
		$dompdf->render();

		return new Response(
			$dompdf->output(),
			200,
			[
				'Content-Type' => 'application/pdf',
				'Content-Disposition' => 'attachment; filename="prescription_radiologie_' . $radiologie->getId() . '.pdf"',
			]
		);
	}
}
