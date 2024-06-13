<?php

namespace App\Repository;

use App\Entity\BudBashCheckAttendance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BudBashCheckAttendance>
 *
 * @method BudBashCheckAttendance|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudBashCheckAttendance|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudBashCheckAttendance[] findAll()
 * @method BudBashCheckAttendance[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudBashCheckAttendanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudBashCheckAttendance::class);
    }

    //    /**
    //     * @return BudBashCheckAttendance[] Returns an array of BudBashCheckAttendance objects
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

    //    public function findOneBySomeField($value): ?BudBashCheckAttendance
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
