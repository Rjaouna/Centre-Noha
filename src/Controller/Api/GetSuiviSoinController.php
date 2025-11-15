<?php

namespace App\Controller\Api;

use App\Repository\SuiviSoinRepository;
use PHPUnit\Metadata\Group;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GetSuiviSoinController extends AbstractController
{
    #[Route('/api/get/suivi/soin/{patient}', name: 'app_api_get_suivi_soin', methods:['GET'])]
    public function index(SuiviSoinRepository $suiviSoin,$patient): Response
    {
        $suivi = $suiviSoin->findBy(['patient' => $patient]);
        return $this->json($suivi, 200,[], [
            'groups' => 'suivi_read'
        ]);
    }
}
