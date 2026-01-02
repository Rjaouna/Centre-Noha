<?php

namespace App\Controller\Api;

use App\Repository\DisponibiliteRepository;
use App\Repository\IndisponibiliteRepository;
use App\Repository\RendezVousRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreneauController extends AbstractController
{
	#[Route('/api/creneaux', name: 'api_creneaux', methods: ['GET'])]
	public function creneaux(
		Request $request,
		UserRepository $userRepo,
		DisponibiliteRepository $dispoRepo,
		IndisponibiliteRepository $indispoRepo,
		RendezVousRepository $rdvRepo
	): JsonResponse {

		try {

			/* ========================
             * 🔐 PARAMÈTRES
             * ======================== */
			$praticienId = $request->query->get('praticien');
			$dateString  = $request->query->get('date');

			if (!$praticienId || !$dateString) {
				return $this->json(['creneaux' => []], 400);
			}

			/* ========================
             * 👤 PRATICIEN
             * ======================== */
			$praticien = $userRepo->find($praticienId);
			if (!$praticien) {
				return $this->json(['creneaux' => []], 404);
			}

			/* ========================
             * 📅 DATE (DateTime MUTABLE)
             * ======================== */
			$date = new \DateTime($dateString);
			$jourSemaine = (int) $date->format('N'); // 1 = lundi, 7 = dimanche

			/* ========================
             * ⛔ WEEK-END (optionnel)
             * ======================== */
			// if ($jourSemaine >= 6) {
			// 	return $this->json(['creneaux' => []]);
			// }

			/* ========================
             * 🟢 DISPONIBILITÉS
             * ======================== */
			$disponibilites = $dispoRepo->findBy([
				'praticien'   => $praticien,
				'jourSemaine' => $jourSemaine,
				'actif'       => true
			]);

			if (!$disponibilites) {
				return $this->json(['creneaux' => []]);
			}

			/* ========================
             * 🔴 RDV EXISTANTS
             * ======================== */
			$rdvs = $rdvRepo->findBy([
				'praticien' => $praticien,
				'date'      => $date,
				'statut'    => 'CONFIRME'
			]);

			/* ========================
             * 🔴 INDISPONIBILITÉS
             * ======================== */
			$indispos = $indispoRepo->findBy([
				'praticien' => $praticien,
				'date'      => $date,
				'actif'     => true
			]);

			/* ========================
             * ⛔ HEURES BLOQUÉES
             * ======================== */
			$blocked = [];

			foreach ($rdvs as $rdv) {
				$blocked[] = $rdv->getHeureDebut()->format('H:i');
			}

			foreach ($indispos as $indispo) {
				if ($indispo->getHeureDebut() && $indispo->getHeureFin()) {
					$t = clone $indispo->getHeureDebut();
					while ($t < $indispo->getHeureFin()) {
						$blocked[] = $t->format('H:i');
						$t->modify('+20 minutes');
					}
				}
			}

			/* ========================
             * ⏱️ GÉNÉRATION CRÉNEAUX
             * ======================== */
			$creneaux = [];

			foreach ($disponibilites as $dispo) {

				$t = clone $dispo->getHeureDebut();

				while ($t < $dispo->getHeureFin()) {

					$time = $t->format('H:i');

					$creneaux[] = [
						'start'    => $time,
						'disabled' => in_array($time, $blocked, true)
					];

					$t->modify('+' . $dispo->getDureeCreneau() . ' minutes');
				}
			}

			return $this->json([
				'creneaux' => $creneaux
			]);
		} catch (\Throwable $e) {

			// ⚠️ LOG EN DEV
			return $this->json([
				'error' => $e->getMessage()
			], 500);
		}
	}
	#[Route('/api/creneaux/count', name: 'api_creneaux_count', methods: ['GET'])]
	public function countByDay(
		Request $request,
		UserRepository $userRepo,
		DisponibiliteRepository $dispoRepo,
		RendezVousRepository $rdvRepo,
		IndisponibiliteRepository $indispoRepo
	): JsonResponse {

		$praticien = $userRepo->find($request->query->get('praticien'));

		if (!$praticien) {
			return $this->json([]);
		}

		$result = [];

		// 4 semaines
		for ($i = 0; $i < 28; $i++) {

			$date = new \DateTime("+$i day");
			$key  = $date->format('Y-m-d');
			$jour = (int) $date->format('N');

			// Week-end : aucun créneau
			if ($jour >= 6) {
				$result[$key] = 0;
				continue;
			}

			$dispos = $dispoRepo->findBy([
				'praticien'   => $praticien,
				'jourSemaine' => $jour,
				'actif'       => true
			]);

			if (!$dispos) {
				$result[$key] = 0;
				continue;
			}

			// Total théorique
			$total = 0;
			foreach ($dispos as $d) {
				$minutes = (
					$d->getHeureFin()->getTimestamp()
					- $d->getHeureDebut()->getTimestamp()
				) / 60;

				$total += (int) ($minutes / $d->getDureeCreneau());
			}

			// RDV existants
			$rdvCount = $rdvRepo->count([
				'praticien' => $praticien,
				'date'      => $date
			]);

			// Indisponibilités
			$indispos = $indispoRepo->findBy([
				'praticien' => $praticien,
				'date'      => $date,
				'actif'     => true
			]);

			foreach ($indispos as $i) {
				if ($i->getHeureDebut() && $i->getHeureFin()) {
					$minutes = (
						$i->getHeureFin()->getTimestamp()
						- $i->getHeureDebut()->getTimestamp()
					) / 60;

					$total -= (int) ($minutes / 20);
				}
			}

			$result[$key] = max(0, $total - $rdvCount);
		}

		return $this->json($result);
	}
}
