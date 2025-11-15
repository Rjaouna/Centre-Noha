<?php
namespace App\Controller;

use App\Entity\Paiement;
use App\Form\PaiementType;
use App\Entity\FicheClient;
use App\Form\FicheClientType;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FicheClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/fiche/client')]
final class FicheClientController extends AbstractController
{
    #[Route(name: 'app_fiche_client_index', methods: ['GET'])]
    public function index(FicheClientRepository $ficheClientRepository): Response
    {
        $fiche = new FicheClient();
        $paiement = new Paiement();
        $form = $this->createForm(FicheClientType::class, $fiche);
        $formPaiement = $this->createForm(PaiementType::class, $paiement);


        return $this->render('fiche_client/index.html.twig', [
            'fiche_clients' => $ficheClientRepository->findAll(),
            'form' => $form->createView(),   // 🔥 clé "form" envoyée au Twig
            'formPaiement' => $formPaiement->createView(), // 🔥 important


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
    public function show(
        FicheClient $ficheClient,
        RendezVousRepository $rdvRepo
    ): Response {

        $now = new \DateTimeImmutable();

        // Récupération des rdv du client
        $rdvs = $rdvRepo->findBy(['client' => $ficheClient], ['dateRdvAt' => 'ASC']);

        // Tri par catégories
        $rdvPast = [];
        $rdvCanceled = [];
        $rdvFuture = [];

        foreach ($rdvs as $rdv) {
            if ($rdv->getStatut() === "annulé") {
                $rdvCanceled[] = $rdv;
            } else if ($rdv->getDateRdvAt() < $now) {
                $rdvPast[] = $rdv;
            } else {
                $rdvFuture[] = $rdv;
            }
        }

        // 🔥 Création du formulaire Paiement
        $paiement = new Paiement();
        $paiement->setClient($ficheClient);

        $formPaiement = $this->createForm(PaiementType::class, $paiement);

        return $this->render('fiche_client/show.html.twig', [
            'fiche_client' => $ficheClient,
            'rdv_total'    => count($rdvs),
            'rdv_past'     => count($rdvPast),
            'rdv_future'   => count($rdvFuture),
            'rdv_canceled' => count($rdvCanceled),
            'rdvs'         => $rdvs,

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
