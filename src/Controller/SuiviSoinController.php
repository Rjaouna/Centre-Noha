<?php

namespace App\Controller;

use App\Entity\FicheClient;
use App\Entity\DossierMedical;
use App\Repository\SuiviSoinRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FicheClientRepository;
use App\Repository\DossierMedicalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\SuiviSoin;               // ✅ La bonne importation
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SuiviSoinController extends AbstractController
{
    #[Route(
        '/patient/{patientId}/dossier/{dossierId}/suivis/api',
        name: 'api_suivis_list',
        methods: ['GET']
    )]
    public function apiListeSuivi(
        int $patientId,
        int $dossierId,
        SuiviSoinRepository $repo,
        DossierMedicalRepository $dossierRepo
    ): JsonResponse {

        $dossier = $dossierRepo->find($dossierId);

        if (!$dossier || $dossier->getPatient()->getId() !== $patientId) {
            return new JsonResponse(['error' => 'Dossier invalide'], 404);
        }

        $suivis = $repo->findBy(
            ['patient' => $patientId, 'dossierMedical' => $dossierId],
            ['createdAt' => 'DESC']
        );

        $data = [];

        foreach ($suivis as $s) {

            // ⭐⭐ AJOUT : médicaments du suivi
            $meds = [];
            foreach ($s->getMedicine() as $m) {
                $meds[] = [
                    'id'   => $m->getId(),
                    'name' => $m->getName(),
                    'code' => $m->getCode(),
                    'dci'  => $m->getDci(),
                    'ppv'  => $m->getPpv(),
                    'forme'  => $m->getForme(),
                    'presentation'  => $m->getPresentation(),
                    'dosage'  => $m->getDosage(),
                    'uniteDosage'  => $m->getUniteDosage(),
                    'isGeneric' => $m->isGeneric(),
                ];
            }

            $data[] = [
                'id'         => $s->getId(),
                'diagnostic' => $s->getDiagnostic(),
                'createdAt'  => $s->getCreatedAt()->format('d/m/Y H:i'),
                'medicines'  => $meds,  // 👈 ajouté ici !
            ];
        }

        return new JsonResponse($data);
    }

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
    ): Response {

        $patient = $ficheRepo->find($patientId);
        $dossier = $dossierRepo->find($dossierId);

        return $this->render('suivi_soin/index.html.twig', [
            'patient' => $patient,
            'dossier' => $dossier,
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
