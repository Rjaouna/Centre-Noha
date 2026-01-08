<?php
namespace App\Controller;

use App\Entity\Paiement;
use App\Form\PaiementType;
use App\Entity\FicheClient;
use App\Form\FicheClientType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FicheClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/fiche/client')]
final class FicheClientController extends AbstractController
{
    #[Route('/', name: 'app_fiche_client_index', methods: ['GET'])]
    public function index(
        FicheClientRepository $ficheClientRepository
    ): Response {
        $fiche = new FicheClient();
        $paiement = new Paiement();

        $form = $this->createForm(FicheClientType::class, $fiche);
        $formPaiement = $this->createForm(PaiementType::class, $paiement);

        return $this->render('fiche_client/index.html.twig', [
            // ⚠️ utile si tu veux fallback non-AJAX
            'fiche_clients' => $ficheClientRepository->findAll(),

            // 🔥 forms pour modals
            'form' => $form->createView(),
            'formPaiement' => $formPaiement->createView(),
        ]);
    }
    #[Route('/ajax', name: 'app_fiche_client_ajax', methods: ['GET'])]
    public function ajax(FicheClientRepository $repo): JsonResponse
    {
        $data = [];

        foreach ($repo->findAll() as $fiche) {
            $isNew = !$fiche->isConsulted();

            $data[] = [
                'id' => $fiche->getId(),
                'nom' => $fiche->getNom(),
                'prenom' => $fiche->getPrenom(),
                'cin' => $fiche->getCin(),
                'ville' => $fiche->getVille(),
                'telephone' => $fiche->getTelephone(),
                'age' => $fiche->getAgePatient(),
                'maladie' => $fiche->getTypeMaladie(),
                'statut' => $fiche->getStatut() ?: null,   // ou 'EN_ATTENTE' si tu veux un défaut
                'isNew' => $isNew,
            ];
        }

        return new JsonResponse([
            'data' => $data
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
            if (!empty($data['age'])) {
                try {
                    $fiche->setAge(new \DateTime($data['age']));
                } catch (\Exception $e) {
                    return $this->json([
                        'error' => 'Format de date invalide'
                    ], 400);
                }
            } else {
                $fiche->setAge(null);
            }

            $fiche->setPoids(isset($data['poids']) ? (string)$data['poids'] : null);
            $fiche->setTelephone($data['telephone'] ?? null);
            $fiche->setDureeMaladie(isset($data['dureeMaladie']) ? (string)$data['dureeMaladie'] : null);
            $fiche->setTypeMaladie($data['typeMaladie'] ?? null);
            $fiche->setTraitement($data['traitement'] ?? null);
            $fiche->setObservation($data['observation'] ?? null);
            $fiche->setIsOpen(false);
            $fiche->setIsConsulted(false);




            // ---- FLOAT (converti ou null)
            $fiche->setPoids(
                isset($data['poids']) && $data['poids'] !== ""
                    ? (string)$data['poids']
                    : null
            );

            // Sauvegarde
            $entityManager->persist($fiche);
            $entityManager->flush();

            return $this->json([
                "success" => true,
                "id" => $fiche->getId(),
                "nom" => $fiche->getNom(),
                "ville" => $fiche->getVille(),
                "telephone" => $fiche->getTelephone(),
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
    public function show(
        FicheClient $ficheClient,
        EntityManagerInterface $em
    ): Response {
        $ficheClient->setIsConsulted(true);
        $ficheClient->setIsOpen(true);
        $em->flush();

        $now = new \DateTimeImmutable();



        // 🔥 Création du formulaire Paiement
        $paiement = new Paiement();
        $paiement->setClient($ficheClient);

        $formPaiement = $this->createForm(PaiementType::class, $paiement);

        return $this->render('fiche_client/show.html.twig', [
            'fiche_client' => $ficheClient,


            // 🔥 On envoie le formulaire à la vue
            'formPaiement' => $formPaiement->createView(),
        ]);
    }



    #[Route('/fiche-client/delete/{id}', name: 'app_fiche_client_delete', methods: ['POST'])]
    public function delete(
        FicheClient $ficheClient,
        EntityManagerInterface $em,
        Request $request
    ): RedirectResponse {

        if ($this->isCsrfTokenValid('delete' . $ficheClient->getId(), $request->request->get('_token'))) {

            $em->remove($ficheClient);
            $em->flush();

            $this->addFlash('success', 'La fiche a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_fiche_client_index');
    }
}
