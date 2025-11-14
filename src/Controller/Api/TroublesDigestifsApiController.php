<?php

namespace App\Controller\Api;

use App\Entity\FicheClient;
use App\Entity\TroublesDigestifs;
use App\Repository\TroublesDigestifsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/troubles-digestifs')]
class TroublesDigestifsApiController extends AbstractController
{
    #[Route('/{idClient}', name: 'api_troubles_digestifs_show', methods: ['GET'])]
    public function show(
        int $idClient,
        TroublesDigestifsRepository $repo
    ): JsonResponse {
        
        $troubles = $repo->findOneBy(['client' => $idClient]);

        if (!$troubles) {
            return new JsonResponse([
                'exists' => false,
                'message' => 'Aucun trouble digestif trouvé pour ce patient.'
            ]);
        }

        return new JsonResponse([
            'exists' => true,
            'data' => [
                'aciditeGastrique' => $troubles->getAciditeGastrique(),
                'constipation'     => $troubles->getConstipation(),
                'diarrhee'         => $troubles->getDiarrhee(),
                'aspectSelles'     => $troubles->getAspectSelles(),
                'gaz'              => $troubles->getGaz(),
                'eructation'       => $troubles->getEructation(),
            ]
        ]);
    }

    #[Route('/add/{id}', name: 'api_digestifs_add', methods: ['POST'])]
    public function add(
        FicheClient $client,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $trouble = new TroublesDigestifs();
        $trouble->setClient($client);

        // ➕ IMPORTANT
        $client->setTroublesDigestifs($trouble);

        $trouble->setAciditeGastrique($data['aciditeGastrique'] ?? null);
        $trouble->setConstipation($data['constipation'] ?? null);
        $trouble->setDiarrhee($data['diarrhee'] ?? null);
        $trouble->setGaz($data['gaz'] ?? null);
        $trouble->setEructation($data['eructation'] ?? null);
        $trouble->setAspectSelles($data['aspectSelles'] ?? null);

        $em->persist($trouble);
        $em->flush();


        return new JsonResponse([
            'success' => true,
            'message' => "Troubles digestifs enregistrés.",
        ]);
    }
    #[Route('/edit/{id}', name: 'api_digestifs_edit', methods: ['POST'])]
    public function editTroubles(FicheClient $fiche, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $d = $fiche->getTroublesDigestifs();

        if (!$d) {
            return new JsonResponse(['success' => false, 'message' => 'Aucun trouble digestif existant']);
        }

        $d->setAciditeGastrique($data['aciditeGastrique'] ?? null);
        $d->setConstipation($data['constipation'] ?? null);
        $d->setDiarrhee($data['diarrhee'] ?? null);
        $d->setGaz($data['gaz'] ?? null);
        $d->setEructation($data['eructation'] ?? null);
        $d->setAspectSelles($data['aspectSelles'] ?? null);

        $em->flush();

        return new JsonResponse(['success' => true]);
    }
}
