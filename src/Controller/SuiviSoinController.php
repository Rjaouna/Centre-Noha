<?php

namespace App\Controller;

use App\Entity\FicheClient;
use App\Entity\SuiviSoin;               // ✅ La bonne importation
use App\Entity\DossierMedical;
use App\Repository\SuiviSoinRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FicheClientRepository;
use App\Repository\DossierMedicalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SuiviSoinController extends AbstractController
{
    #[Route(
        '/patient/{patientId}/dossier/{dossierId}/suivis',
        name: 'app_suivi_par_dossier',
        methods: ['GET']
    )]
    public function index(
        int $patientId,
        int $dossierId,
        FicheClientRepository $ficheRepo,
        DossierMedicalRepository $dossierRepo,
        SuiviSoinRepository $suiviRepo
    ): Response {

        $patient = $ficheRepo->find($patientId);
        if (!$patient) {
            throw $this->createNotFoundException("Patient introuvable.");
        }

        $dossier = $dossierRepo->find($dossierId);
        if (!$dossier) {
            throw $this->createNotFoundException("Dossier médical introuvable.");
        }

        if ($dossier->getPatient()->getId() !== $patient->getId()) {
            throw $this->createAccessDeniedException("Ce dossier n'appartient pas à ce patient.");
        }

        $suivis = $suiviRepo->findBy([
            'patient' => $patient,
            'dossierMedical' => $dossier
        ], [
            'createdAt' => 'DESC'
        ]);

        return $this->render('suivi_soin/index.html.twig', [
            'patient' => $patient,
            'dossier' => $dossier,
            'suivis' => $suivis,
        ]);
    }

    #[Route(
        '/api/patient/{patientId}/dossier/{dossierId}/suivi/create',
        name: 'api_suivi_create',
        methods: ['POST']
    )]
    public function createSuivi(
        int $patientId,
        int $dossierId,
        Request $request,
        EntityManagerInterface $em,
        FicheClientRepository $ficheRepo,
        DossierMedicalRepository $dossierRepo
    ): Response {

        $patient = $ficheRepo->find($patientId);
        $dossier = $dossierRepo->find($dossierId);

        if (!$patient || !$dossier) {
            return $this->json(['success' => false, 'message' => 'Patient ou dossier introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $suivi = new SuiviSoin();
        $suivi->setPatient($patient);
        $suivi->setDossierMedical($dossier);
        $suivi->setDiagnostic($data['diagnostic'] ?? null);

        $em->persist($suivi);
        $em->flush();

        return $this->json([
            'success' => true,
            'id' => $suivi->getId(),
            'diagnostic' => $suivi->getDiagnostic(),
            'createdAt' => $suivi->getCreatedAt()?->format('d/m/Y H:i'),
        ]);
    }
}
