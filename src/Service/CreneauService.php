<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\DisponibiliteRepository;
use App\Repository\IndisponibiliteRepository;
use App\Repository\RendezVousRepository;

class CreneauService
{
	public function __construct(
		private DisponibiliteRepository $dispoRepo,
		private IndisponibiliteRepository $indispoRepo,
		private RendezVousRepository $rdvRepo
	) {}

	public function getCreneaux(User $praticien, \DateTimeImmutable $date): array
	{
		$jour = (int) $date->format('N'); // 1..7

		$dispos = $this->dispoRepo->findBy([
			'praticien'   => $praticien,
			'jourSemaine' => $jour,
			'actif'       => true,
		]);

		if (!$dispos) {
			return [];
		}

		$creneaux = [];

		foreach ($dispos as $dispo) {

			// 🔒 Sécurité absolue
			if (
				!$dispo->getHeureDebut() ||
				!$dispo->getHeureFin() ||
				!$dispo->getDureeCreneau() ||
				$dispo->getDureeCreneau() <= 0
			) {
				continue;
			}

			$start = \DateTimeImmutable::createFromMutable($dispo->getHeureDebut());
			$end   = \DateTimeImmutable::createFromMutable($dispo->getHeureFin());
			$step  = $dispo->getDureeCreneau();

			while ($start < $end) {

				$next = $start->modify('+' . $step . ' minutes');
				if ($next > $end) {
					break;
				}

				$creneaux[] = [
					'start' => $start->format('H:i'),
					'end'   => $next->format('H:i'),
				];

				$start = $next;
			}
		}

		return $creneaux;
	}


	private function isBloque($start, $end, $indispos, $rdvs): bool
	{
		foreach ($indispos as $i) {
			if ($i->getHeureDebut() === null) return true;

			if (
				$start < $i->getHeureFin() &&
				$end > $i->getHeureDebut()
			) return true;
		}

		foreach ($rdvs as $r) {
			if (
				$start < $r->getHeureFin() &&
				$end > $r->getHeureDebut()
			) return true;
		}

		return false;
	}
}
