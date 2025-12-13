<?php // src/Controller/Admin/ImportDispositifController.php
namespace App\Controller\Admin;

use App\Entity\DispositifMedical;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImportDispositifController extends AbstractController
{
	#[Route('/admin/import-dispositifs', name: 'admin_import_dispositifs', methods: ['GET', 'POST'])]
	public function import(Request $request, EntityManagerInterface $em): Response
	{
		if ($request->isMethod('POST')) {

			$file = $request->files->get('excel');
			if (!$file) {
				$this->addFlash('danger', 'Aucun fichier fourni');
				return $this->redirectToRoute('admin_import_dispositifs');
			}

			$spreadsheet = IOFactory::load($file->getPathname());
			$sheet = $spreadsheet->getActiveSheet();
			$rows = $sheet->toArray();

			// Supprimer l’en-tête
			unset($rows[0]);

			foreach ($rows as $row) {

				if (empty($row[0])) continue;

				$entity = new DispositifMedical();
				$entity->setCodeCnops(trim($row[0]));
				$entity->setCodeAnam(trim($row[1]));
				$entity->setLibelle(trim($row[2]));

				// Nettoyage 12,000.00 → 12000.00
				$tarif = str_replace([',', ' '], '', $row[3]);
				$entity->setTarifReference((float) $tarif);

				$entity->setAccordPrealable(trim($row[4]));
				$entity->setPiecesAFournir(trim($row[5]));
				$entity->setPiecesComplementaires($row[6] ?? null);

				$em->persist($entity);
			}

			$em->flush();

			$this->addFlash('success', 'Import terminé avec succès');
			return $this->redirectToRoute('admin_import_dispositifs');
		}

		return $this->render('admin/import_dispositifs.html.twig');
	}
}
