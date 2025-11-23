<?php

namespace App\Controller;

use App\Entity\FicheClient;
use App\Entity\DossierMedical;
use App\Form\DossierMedicalType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DossierMedicalRepository;
use App\Repository\FicheClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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
            'patient' => $patient
        ]);
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
}
