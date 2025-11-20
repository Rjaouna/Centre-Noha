<?php

namespace App\Controller\Api\Plugins;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FicheClientRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class GetNewAdmissionsController extends AbstractController
{
    #[Route('/api/plugins/get/new/admissions', name: 'app_api_plugins_get_new_admissions')]
    public function index(FicheClientRepository $ficheClient): JsonResponse
    {
        $newAdmission = $ficheClient->findBy(['isOpen' => false]);

        return $this->json($newAdmission, 200, [], [
            'groups' => 'admission_read'
        ]);
    }

    #[Route('/api/plugins/admission/validate-all', name: 'api_admission_validate_all', methods: ['POST'])]
    public function validateAll(
        FicheClientRepository $ficheRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        // Récupérer toutes les fiches non ouvertes
        $admissions = $ficheRepo->findBy(['isOpen' => false]);

        if (empty($admissions)) {
            return new JsonResponse([
                'success' => true,
                'updated' => 0,
                'message' => 'Aucune admission à valider'
            ]);
        }

        // Mise à jour en boucle
        foreach ($admissions as $admission) {
            $admission->setIsOpen(true);
            $em->persist($admission);
        }

        $em->flush();

        return new JsonResponse([
            'success' => true,
            'updated' => count($admissions),
            'message' => 'Toutes les admissions ont été validées'
        ]);
    }
}
