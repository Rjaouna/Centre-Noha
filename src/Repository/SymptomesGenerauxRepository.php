<?php

namespace App\Repository;

use App\Entity\SymptomesGeneraux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SymptomesGeneraux>
 */
class SymptomesGenerauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SymptomesGeneraux::class);
    }
    public function countByField(string $field): int
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->where("s.$field IS NOT NULL")
            ->andWhere("s.$field != ''")
            ->getQuery()
            ->getSingleScalarResult();
    }


    //    /**
    //     * @return SymptomesGeneraux[] Returns an array of SymptomesGeneraux objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SymptomesGeneraux
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
