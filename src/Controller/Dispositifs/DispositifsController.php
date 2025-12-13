<?php

namespace App\Controller\Dispositifs;

use App\Repository\DispositifMedicalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class DispositifsController extends AbstractController
{
	#[Route('/dispositif/search', name: 'dispositif_search')]
	public function index()
	{
		return $this->render('dispositif/search.html.twig');
	}

	#[Route('/dispositif/search/api', name: 'dispositif_search_api')]
	public function search(Request $request, DispositifMedicalRepository $repo): JsonResponse
	{
		$search = trim($request->query->get('search', ''));

		if (mb_strlen($search) < 2) {
			return $this->json([]);
		}

		$results = $repo->createQueryBuilder('d')
			->where('LOWER(d.libelle) LIKE :q')
			->setParameter('q', '%' . mb_strtolower($search) . '%')
			->setMaxResults(20)
			->getQuery()
			->getResult();

		return $this->json(array_map(static fn($d) => [
			'id'        => $d->getId(),
			'libelle'   => $d->getLibelle(),
			'codeCnops' => $d->getCodeCnops(),
			'codeAnam'  => $d->getCodeAnam(),
			'tarif'     => (float) $d->getTarifReference(),
			'ap'        => $d->getAccordPrealable(),
		], $results));
	}
}
