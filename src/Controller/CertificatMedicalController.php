<?php

namespace App\Controller;

use App\Entity\CertificatMedical;
use App\Entity\FicheClient;
use App\Entity\Patient;
use App\Repository\CertificatMedicalRepository;
use App\Repository\CabinetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/certificats')]
class CertificatMedicalController extends AbstractController
{
	/**
	 * 📌 PAGE INDEX (liste + modal)
	 */
	#[Route('/', name: 'certificats_index', methods: ['GET'])]
	public function index(
		CertificatMedicalRepository $repo
	): Response {
		return $this->render('certificats/index.html.twig', [
			'certificats' => $repo->findAll()
		]);
	}

	#[Route('/certificats/ajax/list', name: 'certificats_ajax_list', methods: ['GET'])]
public function ajaxList(CertificatMedicalRepository $repo): JsonResponse
{
    $certificats = $repo->findAll();

    $data = [];

    foreach ($certificats as $certificat) {
        $data[] = [
            'id'    => $certificat->getId(),
            'titre' => $certificat->getTitre(),
        ];
    }

    return new JsonResponse($data);
}

	



	/**
	 * 📌 API : récupérer un certificat (EDIT AJAX)
	 */
	#[Route('/api/{id}', name: 'certificats_api_show', methods: ['GET'])]
	public function apiShow(
		CertificatMedical $certificat
	): JsonResponse {
		return new JsonResponse([
			'id' => $certificat->getId(),
			'titre' => $certificat->getTitre(),
			'contenu' => $certificat->getContenu()
		]);
	}

	/**
	 * 📌 CREATE / UPDATE (AJAX)
	 */
	#[Route('/save', name: 'certificats_save', methods: ['POST'])]
	public function save(
		Request $request,
		EntityManagerInterface $em,
		CertificatMedicalRepository $repo
	): JsonResponse {
		$id = $request->request->get('id');

		$certificat = $id
			? $repo->find($id)
			: new CertificatMedical();

		if (!$certificat) {
			return new JsonResponse(['error' => 'Certificat introuvable'], 404);
		}

		$certificat->setTitre($request->request->get('titre'));
		$certificat->setContenu($request->request->get('contenu'));

		$em->persist($certificat);
		$em->flush();

		return new JsonResponse(['success' => true]);
	}

	/**
	 * 📌 DELETE (AJAX)
	 */
	#[Route('/delete/{id}', name: 'certificats_delete', methods: ['DELETE'])]
	public function delete(
		CertificatMedical $certificat,
		EntityManagerInterface $em
	): JsonResponse {
		$em->remove($certificat);
		$em->flush();

		return new JsonResponse(['success' => true]);
	}

	/**
	 * 🖨️ IMPRESSION CERTIFICAT DEPUIS FICHE PATIENT
	 */
	#[Route('/print/patient/{patient}/{certificat}', name: 'certificat_print', methods: ['GET'])]
	public function print(
		FicheClient $patient,
		CertificatMedical $certificat,
		CabinetRepository $cabRepo
	): Response {
		return $this->render('certificats/print.html.twig', [
			'patient'   => $patient,
			'certificat' => $certificat,
			'medecin'   => $this->getUser(),
			'cabinet'   => $cabRepo->findOneBy([])
		]);
	}
}
