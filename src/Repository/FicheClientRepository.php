<?php

namespace App\Repository;

use App\Entity\FicheClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FicheClient>
 */
class FicheClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheClient::class);
    }
    public function countByTypeMaladie(string $type): int
    {
        return $this->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->andWhere('f.typeMaladie = :t')
            ->setParameter('t', $type)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAutresMaladies(): int
    {
        return $this->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->andWhere('f.typeMaladie NOT IN(:list)')
            ->setParameter('list', ['Aiguë', 'Chronique'])
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAllMaladies(): array
    {
        return $this->createQueryBuilder('f')
            ->select('f.typeMaladie AS typeMaladie, COUNT(f.id) AS count')
            ->groupBy('f.typeMaladie')
            ->orderBy('count', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function findWaitingRoomToday(\DateTimeInterface $today): array
    {
        // On veut les RDV du jour (DATE only)
        return $this->createQueryBuilder('c')
            ->leftJoin('c.rendezVouses', 'r', 'WITH', 'r.date = :today')
            ->addSelect('r')
            ->andWhere('c.Statut IS NOT NULL OR r.id IS NOT NULL')
            ->setParameter('today', $today->format('Y-m-d')) // important pour DATE
            ->orderBy('c.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }





    //    /**
    //     * @return FicheClient[] Returns an array of FicheClient objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FicheClient
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
