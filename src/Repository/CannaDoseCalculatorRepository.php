<?php

namespace App\Repository;

use App\Entity\CannaDoseCalculator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CannaDoseCalculator>
 *
 * @method CannaDoseCalculator|null find($id, $lockMode = null, $lockVersion = null)
 * @method CannaDoseCalculator|null findOneBy(array $criteria, array $orderBy = null)
 * @method CannaDoseCalculator[] findAll()
 * @method CannaDoseCalculator[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CannaDoseCalculatorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CannaDoseCalculator::class);
    }

    //    /**
    //     * @return CannaDoseCalculator[] Returns an array of CannaDoseCalculator objects
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

    //    public function findOneBySomeField($value): ?CannaDoseCalculator
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
