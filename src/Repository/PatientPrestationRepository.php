<?php

namespace App\Repository;

use App\Entity\FicheClient;
use App\Entity\PatientPrestation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PatientPrestation>
 *
 * @method PatientPrestation|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatientPrestation|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatientPrestation[]    findAll()
 * @method PatientPrestation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientPrestationRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, PatientPrestation::class);
	}

	/**
	 * @return PatientPrestation[]
	 */
	public function findByPatient(FicheClient $patient, int $limit = 200): array
	{
		return $this->createQueryBuilder('pp')
			->andWhere('pp.patient = :patient')
			->setParameter('patient', $patient)
			->orderBy('pp.createdAt', 'DESC')
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}

	public function sumTotalByPatient(FicheClient $patient): float
	{
		$res = $this->createQueryBuilder('pp')
			->select('COALESCE(SUM(pp.prixUnitaire * pp.quantite), 0) as total')
			->andWhere('pp.patient = :patient')
			->setParameter('patient', $patient)
			->getQuery()
			->getSingleScalarResult();

		return (float) $res;
	}
}
