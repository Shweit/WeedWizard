<?php

namespace App\Repository;

use App\Entity\CannabisVerein;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CannabisVerein>
 *
 * @method CannabisVerein|null find($id, $lockMode = null, $lockVersion = null)
 * @method CannabisVerein|null findOneBy(array $criteria, array $orderBy = null)
 * @method CannabisVerein[] findAll()
 * @method CannabisVerein[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CannabisVereinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CannabisVerein::class);
    }

    //    /**
    //     * @return CannabisVerein[] Returns an array of CannabisVerein objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CannabisVerein
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
