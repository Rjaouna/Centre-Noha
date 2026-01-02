<?php 
// src/Controller/Api/DisponibiliteApiController.php
namespace App\Controller\Api;

use App\Entity\Disponibilite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/disponibilites')]
class DisponibiliteApiController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $dispos = $em->getRepository(Disponibilite::class)
            ->findBy(['praticien' => $this->getUser()], ['jourSemaine' => 'ASC']);

        $data = array_map(fn (Disponibilite $d) => [
            'id' => $d->getId(),
            'jourSemaine' => $d->getJourSemaine(),
            'heureDebut' => $d->getHeureDebut()->format('H:i'),
            'heureFin' => $d->getHeureFin()->format('H:i'),
            'dureeCreneau' => $d->getDureeCreneau(),
            'actif' => $d->isActif(),
        ], $dispos);

        return $this->json($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getOne(Disponibilite $dispo): JsonResponse
    {
        return $this->json([
            'id' => $dispo->getId(),
            'jourSemaine' => $dispo->getJourSemaine(),
            'heureDebut' => $dispo->getHeureDebut()->format('H:i'),
            'heureFin' => $dispo->getHeureFin()->format('H:i'),
            'dureeCreneau' => $dispo->getDureeCreneau(),
            'actif' => $dispo->isActif(),
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dispo = (new Disponibilite())
            ->setPraticien($this->getUser())
            ->setJourSemaine((int) $data['jourSemaine'])
            ->setHeureDebut(new \DateTime($data['heureDebut']))
            ->setHeureFin(new \DateTime($data['heureFin']))
            ->setDureeCreneau((int) $data['dureeCreneau'])
            ->setActif($data['actif']);

        $em->persist($dispo);
        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Disponibilite $dispo,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $dispo
            ->setJourSemaine((int) $data['jourSemaine'])
            ->setHeureDebut(new \DateTime($data['heureDebut']))
            ->setHeureFin(new \DateTime($data['heureFin']))
            ->setDureeCreneau((int) $data['dureeCreneau'])
            ->setActif($data['actif']);

        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Disponibilite $dispo, EntityManagerInterface $em): JsonResponse
    {
        if ($dispo->getPraticien() !== $this->getUser()) {
            return $this->json(['error' => 'Accès refusé'], 403);
        }

        $em->remove($dispo);
        $em->flush();

        return $this->json(['success' => true]);
    }
}
