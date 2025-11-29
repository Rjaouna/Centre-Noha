<?php

namespace App\Controller\Medicine;

use App\Entity\Medicine;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicineImportController extends AbstractController
{
	#[Route('/medicine/import', name: 'medicine_import_form')]
	public function importForm(): Response
	{
		return $this->render('medicine/import.html.twig');
	}

	#[Route('/medicine/import/process', name: 'medicine_import_process', methods: ['POST'])]
	public function importProcess(
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {

		/** @var UploadedFile $file */
		$file = $request->files->get('file');

		if (!$file) {
			return new JsonResponse(['error' => 'Aucun fichier reçu'], 400);
		}

		try {
			$reader = IOFactory::createReader('Xlsx');
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($file->getPathname());
			$sheet = $spreadsheet->getActiveSheet();

			$batchSize = 1000;
			$count = 0;

			foreach ($sheet->getRowIterator() as $rowIndex => $row) {

				if ($rowIndex === 1) continue; // header

				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);

				$cells = [];
				foreach ($cellIterator as $cell) {
					$cells[] = trim((string)$cell->getValue());
				}

				if (empty($cells[0])) continue;

				$medicine = new Medicine();
				$medicine->setCode($cells[0]);
				$medicine->setName($cells[1]);
				$medicine->setDci($cells[2]);
				$medicine->setDosage($cells[3]);
				$medicine->setUniteDosage($cells[4]);
				$medicine->setForme($cells[5]);
				$medicine->setPresentation($cells[6]);
				$medicine->setPpv(floatval(str_replace(',', '.', $cells[7])));
				$medicine->setPh(floatval(str_replace(',', '.', $cells[8])));
				$medicine->setIsGeneric($cells[10] === 'G');
				$medicine->setTauxRembourssement(intval(str_replace('%', '', $cells[11])));

				$em->persist($medicine);
				$count++;

				// 🟦 Quand 1000 lignes sont prêtes → flush + clear
				if ($count % $batchSize === 0) {
					$em->flush();
					$em->clear();

					// Facultatif : pause 100ms pour éviter surcharge CPU
					usleep(100000);
				}
			}

			// Flush final pour les derniers enregistrements
			$em->flush();
			$em->clear();

			return new JsonResponse(['imported' => $count]);
		} catch (\Exception $e) {
			return new JsonResponse(['error' => $e->getMessage()], 500);
		}
	}
}
