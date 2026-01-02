<?php

namespace App\Repository;

use App\Entity\Indisponibilite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Indisponibilite>
 */
class IndisponibiliteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Indisponibilite::class);
    }
    public function findByDateAndPraticien(\DateTimeImmutable $date, User $user)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.date = :date')
            ->andWhere('i.praticien = :user')
            ->andWhere('i.actif = true')
            ->setParameter('date', $date)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Indisponibilite[] Returns an array of Indisponibilite objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Indisponibilite
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
