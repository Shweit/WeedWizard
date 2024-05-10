<?php

namespace App\Repository;

use App\Entity\BudBash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BudBash>
 *
 * @method BudBash|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudBash|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudBash[] findAll()
 * @method BudBash[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudBashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudBash::class);
    }

    //    /**
    //     * @return BudBash[] Returns an array of BudBash objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BudBash
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
