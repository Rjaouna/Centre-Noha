<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\RendezVous;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<RendezVous>
 */
class RendezVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }
    public function findActifsByDate(User $praticien, \DateTimeImmutable $date)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.praticien = :p')
            ->andWhere('r.date = :d')
            ->andWhere('r.statut != :annule')
            ->setParameter('p', $praticien)
            ->setParameter('d', $date)
            ->setParameter('annule', 'annule')
            ->getQuery()
            ->getResult();
    }

    // src/Repository/RendezVousRepository.php
    public function findConfirmedByPraticienOrdered(User $praticien): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.praticien = :p')
            ->andWhere('r.statut = :s')
            ->setParameter('p', $praticien)
            ->setParameter('s', 'confirme')
            ->orderBy('r.date', 'ASC')
            ->addOrderBy('r.heureDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }



    //    /**
    //     * @return RendezVous[] Returns an array of RendezVous objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RendezVous
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
