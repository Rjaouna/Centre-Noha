<?php

namespace App\Controller;

use App\Entity\FicheClient;
use App\Entity\DossierMedical;
use App\Form\DossierMedicalType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FicheClientRepository;
use App\Repository\DossierMedicalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/dossier/medical')]
final class DossierMedicalController extends AbstractController
{
    #[Route('/patient/{id}/dossiers', name: 'app_dossier_medical_index', methods:['GET'])]
    public function index(
        FicheClientRepository $ficheRepo,
        DossierMedicalRepository $repo,
        int $id
    ): Response {
        // Récupération du patient
        $patient = $ficheRepo->find($id);

        if (!$patient) {
            throw $this->createNotFoundException("Patient introuvable.");
        }

        // Récupération des dossiers liés à ce patient
        $dossiers = $repo->findBy(['patient' => $patient]);

        return $this->render('dossier_medical/index.html.twig', [
            'dossiers' => $dossiers,
            'patient' => $patient,
        ]);
    }

    #[Route('/api/dossier-medical/{id}', name: 'api_dossier_medical_add', methods: ['POST'])]
    public function add(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        FicheClientRepository $patientRepository
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['titre'])) {
            return $this->json([
                'error' => 'Payload invalide',
                'raw' => $request->getContent()
            ], 400);
        }

        $patient = $patientRepository->find($id);
        if (!$patient) {
            return $this->json(['error' => 'Patient introuvable'], 404);
        }
        // 1) Récupérer tous les dossiers du patient
        $dossiers = $patient->getDossierMedicals();

        foreach ($dossiers as $d) {
            $d->setIsActif(false);
        }


        $dossier = new DossierMedical();
        $dossier->setTitre($data['titre']);
        $dossier->setDescription($data['description'] ?? null);
        $dossier->setPatient($patient);
        $dossier->setCreatedAt(new \DateTimeImmutable());
        $dossier->setIsActif(true);

        $em->persist($dossier);
        $em->flush();

        return $this->json([
            'id' => $dossier->getId(),
            'titre' => $dossier->getTitre(),
            'description' => $dossier->getDescription(),
            'createdAt' => $dossier->getCreatedAt()->format('d/m/Y'),
            'isActif' => $dossier->isActif(),
            'suiviCount' => 0
        ]);
    }
    #[Route('/api/dossier-medical/{id}', name: 'api_dossier_medical_delete', methods: ['DELETE'])]
    public function delete(
        int $id,
        DossierMedicalRepository $repo,
        EntityManagerInterface $em
    ): JsonResponse {
        $dossier = $repo->find($id);

        if (!$dossier) {
            return $this->json(['success' => false, 'message' => 'Dossier introuvable'], 404);
        }


        $em->remove($dossier);
        $em->flush();

        return $this->json(['success' => true, 'message' => 'Dossier supprimé']);
    }


    #[Route('/api/patient/{id}/dossiers', name: 'api_dossier_medical_by_patient', methods: ['GET'])]
    public function apiByPatient(
        int $id,
        DossierMedicalRepository $repo,
        FicheClientRepository $ficheRepo
    ): Response {

        $patient = $ficheRepo->find($id);

        if (!$patient) {
            return $this->json(['error' => 'Patient introuvable'], 404);
        }

        $dossiers = $repo->findBy(['patient' => $patient]);
        $data = [];

        foreach ($dossiers as $d) {
            $data[] = [
                'id' => $d->getId(),
                'titre' => $d->getTitre(),
                'isActif' => $d->isActif(),
                'description' => $d->getDescription(),
                'createdAt' => $d->getCreatedAt()?->format('Y-m-d H:i'),
                'updatedAt' => $d->getUpdatedAt()?->format('Y-m-d H:i'),
                'suiviCount' => $d->getSuiviSoins()->count()
            ];
        }

        return $this->json($data);
    }


    #[Route('/api/patient/{id}/dossier/create', name: 'api_dossier_medical_create', methods: ['POST'])]
    public function create($id, Request $request, EntityManagerInterface $em, FicheClientRepository $ficheRepo): Response
    {
        $patient = $ficheRepo->find($id);

        if (!$patient) {
            return $this->json(['success' => false, 'message' => "Patient introuvable"], 404);
        }

        $data = json_decode($request->getContent(), true);

        $dossier = new DossierMedical();
        $dossier->setTitre($data['titre'] ?? null);
        $dossier->setDescription($data['description'] ?? null);
        $dossier->setPatient($patient);   // 🔥 important

        $em->persist($dossier);
        $em->flush();

        return $this->json([
            'success' => true,
            'id' => $dossier->getId(),
            'titre' => $dossier->getTitre(),
            'description' => $dossier->getDescription(),
            'createdAt' => $dossier->getCreatedAt()?->format('d/m/Y H:i'),
        ]);
    }
    #[Route('/api/dossier-medical/{id}/actif', name: 'api_dossier_medical_set_actif', methods: ['PATCH'])]
    public function setActif(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        DossierMedicalRepository $dossierRepo
    ): JsonResponse {
        $payload = json_decode($request->getContent(), true) ?? [];

        if (!array_key_exists('isActif', $payload)) {
            return $this->json(['success' => false, 'error' => 'Champ isActif manquant'], 400);
        }

        $isActif = filter_var($payload['isActif'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        if ($isActif === null) {
            return $this->json(['success' => false, 'error' => 'isActif invalide (true/false)'], 400);
        }

        $dossier = $dossierRepo->find($id);
        if (!$dossier) {
            return $this->json(['success' => false, 'error' => 'Dossier introuvable'], 404);
        }

        $patient = $dossier->getPatient();

        if ($isActif) {
            // Désactive tous les dossiers du patient
            foreach ($patient->getDossierMedicals() as $d) {
                $d->setIsActif(false);
            }
            // Active celui-ci
            $dossier->setIsActif(true);
        } else {
            // Désactive seulement celui-ci
            $dossier->setIsActif(false);
        }

        $em->flush();

        return $this->json([
            'success' => true,
            'id' => $dossier->getId(),
            'isActif' => $dossier->isActif(),
        ]);
    }
}
