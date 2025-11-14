<?php
namespace App\Controller;

use App\Entity\FicheClient;
use App\Form\FicheClientType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FicheClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/fiche/client')]
final class FicheClientController extends AbstractController
{
    #[Route(name: 'app_fiche_client_index', methods: ['GET'])]
    public function index(FicheClientRepository $ficheClientRepository): Response
    {
        $fiche = new FicheClient();
        $form = $this->createForm(FicheClientType::class, $fiche);

        return $this->render('fiche_client/index.html.twig', [
            'fiche_clients' => $ficheClientRepository->findAll(),
            'form' => $form->createView(),   // 🔥 clé "form" envoyée au Twig
        ]);
    }

    #[Route('/new', name: 'app_fiche_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // 📌 Requête AJAX JSON envoyée avec fetch()
        if ($request->getContentTypeFormat() === 'json') {

            $data = json_decode($request->getContent(), true);

            $fiche = new FicheClient();

            // ---- STRINGS (jamais null)
            $fiche->setNom($data['nom'] ?? null);
            $fiche->setVille($data['ville'] ?? null);
            $fiche->setAge(isset($data['age']) ? (int)$data['age'] : null);
            $fiche->setPoids(isset($data['poids']) ? (int)$data['poids'] : null);
            $fiche->setTelephone($data['telephone'] ?? null);
            $fiche->setDureeMaladie(isset($data['dureeMaladie']) ? (int)$data['dureeMaladie'] : null);
            $fiche->setTypeMaladie($data['typeMaladie'] ?? null);
            $fiche->setTraitement($data['traitement'] ?? null);
            $fiche->setObservation($data['observation'] ?? null);


            // ---- INT (converti ou null)
            $fiche->setAge(
                isset($data['age']) && $data['age'] !== ""
                    ? (int)$data['age']
                    : null
            );

            // ---- FLOAT (converti ou null)
            $fiche->setPoids(
                isset($data['poids']) && $data['poids'] !== ""
                    ? (float)$data['poids']
                    : null
            );

            // Sauvegarde
            $entityManager->persist($fiche);
            $entityManager->flush();

            return $this->json([
                "success" => true,
                "id" => $fiche->getId(),
                "nom" => $fiche->getNom(),
                "message" => "Fiche créée avec succès"
            ]);
        }

        // 📌 Vue normale
        $ficheClient = new FicheClient();
        $form = $this->createForm(FicheClientType::class, $ficheClient);

        return $this->render('fiche_client/new.html.twig', [
            'fiche_client' => $ficheClient,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_fiche_client_show', methods: ['GET'])]
    public function show(FicheClient $ficheClient): Response
    {
        return $this->render('fiche_client/show.html.twig', [
            'fiche_client' => $ficheClient,
        ]);
    }
}
