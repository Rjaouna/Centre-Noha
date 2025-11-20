<?php

namespace App\DataFixtures;

use App\Entity\FicheClient;
use App\Entity\TroublesDigestifs;
use App\Entity\SymptomesGeneraux;
use App\Entity\MaladiesChroniques;
use App\Entity\Paiement;
use App\Entity\RendezVous;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Faker version Maroc 🇲🇦
        $faker = Factory::create('fr_FR');

        // Villes marocaines réalistes
        $villes = [
            'Casablanca',
            'Rabat',
            'Tanger',
            'Marrakech',
            'Fès',
            'Agadir',
            'Oujda',
            'Kenitra',
            'Tetouan',
            'Mohammedia',
            'Safi',
            'El Jadida'
        ];

        // 20 clients
        for ($i = 0; $i < 20; $i++) {

            // ------------ CLIENT ------------
            $client = new FicheClient();
            $client->setNom($faker->firstName . ' ' . $faker->lastName);
            $client->setVille($faker->randomElement($villes));
            $client->setAge($faker->numberBetween(18, 80));
            $client->setPoids($faker->numberBetween(55, 110));
            $client->setTelephone('06' . $faker->numberBetween(10000000, 99999999));
            $client->setDureeMaladie($faker->numberBetween(1, 24));
            $client->setTypeMaladie($faker->randomElement(['Diabète', 'Hypertension', 'Migraine', 'Stress', 'Aucune']));
            $client->setTraitement($faker->randomElement(['Doliprane', 'Ibuprofène', 'Tisane', 'Repos', null]));
            $client->setObservation($faker->sentence(10));
            $client->isOpen(true);
            $client->isOpen(true);

            $manager->persist($client);

            // ------------ TROUBLES DIGESTIFS ------------
            $digestif = new TroublesDigestifs();
            $digestif->setClient($client);
            $digestif->setAciditeGastrique($faker->randomElement(['Oui', 'Non']));
            $digestif->setConstipation($faker->randomElement(['Oui', 'Non']));
            $digestif->setDiarrhee($faker->randomElement(['Oui', 'Non']));
            $digestif->setAspectSelles($faker->randomElement(['Normales', 'Dures', 'Molles']));
            $digestif->setGaz($faker->randomElement(['Oui', 'Non']));
            $digestif->setEructation($faker->randomElement(['Oui', 'Non']));

            $manager->persist($digestif);

            // ------------ SYMPTOMES GENERAUX ------------
            $symp = new SymptomesGeneraux();
            $symp->setClient($client);
            $symp->setMauxTete($faker->randomElement(['Oui', 'Non']));
            $symp->setMauxNuque($faker->randomElement(['Oui', 'Non']));
            $symp->setInsomnie($faker->randomElement(['Oui', 'Non']));
            $symp->setHemorroides($faker->randomElement(['Oui', 'Non']));
            $symp->setEnuresie($faker->randomElement(['Oui', 'Non']));

            $manager->persist($symp);

            // ------------ MALADIES CHRONIQUES ------------
            $maladie = new MaladiesChroniques();
            $maladie->setClient($client);
            $maladie->setDiabetique($faker->randomElement(['Oui', 'Non']));
            $maladie->setHypertendu($faker->randomElement(['Oui', 'Non']));
            $maladie->setCholesterol($faker->randomElement(['Oui', 'Non']));
            $maladie->setAllergieNasale($faker->randomElement(['Oui', 'Non']));
            $maladie->setAutreMaladie($faker->sentence(5));

            $manager->persist($maladie);

            // ------------ PAIEMENTS (entre 0 et 3 paiements) ------------
            $nbPaiements = random_int(0, 3);

            for ($p = 0; $p < $nbPaiements; $p++) {
                $paiement = new Paiement();
                $paiement->setClient($client);
                $paiement->setPrixTotal($faker->numberBetween(200, 800) . ' dh');
                $paiement->setMontantPaye($faker->numberBetween(50, 500) . ' dh');
                $paiement->setReste($faker->numberBetween(0, 300) . ' dh');
                $paiement->setTypePaiement($faker->randomElement(['Espèces', 'Carte', 'Virement']));

                $manager->persist($paiement);
            }

            // ------------ RDV ------------
            $nbRdv = random_int(1, 5);

            for ($r = 0; $r < $nbRdv; $r++) {

                $statut = $faker->randomElement(['A venir', 'Annulé', 'Passé']);

                $rdv = new RendezVous();
                $rdv->setClient($client);
                $rdv->setMotif($faker->randomElement(['Consultation', 'Suivi', 'Douleur', 'Diagnostic']));
                $rdv->setStatut($statut);
                $rdv->setCommentaire($faker->sentence(8));

                // Dates réalistes
                if ($statut === 'A venir') {
                    $rdv->setDateRdvAt(new \DateTimeImmutable('+ ' . rand(1, 30) . ' days'));
                } elseif ($statut === 'Passé') {
                    $rdv->setDateRdvAt(new \DateTimeImmutable('- ' . rand(1, 30) . ' days'));
                } else {
                    $rdv->setDateRdvAt(new \DateTimeImmutable('- ' . rand(1, 15) . ' days'));
                }

                $manager->persist($rdv);
            }
        }

        $manager->flush();
    }
}
