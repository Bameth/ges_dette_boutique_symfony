<?php

namespace App\Repository;

use App\Entity\Dette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dette>
 */
class DetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dette::class);
    }

    public function paginateDetteClients(int $page = 0, int $limit = 2): array
    {
        $pageOffset = ($page - 1) * $limit;

        $totalClients = (int) $this->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $query = $this->createQueryBuilder('d')
            ->leftJoin('d.client', 'c')
            ->addSelect('c')
            ->orderBy('d.id', 'ASC')
            ->setFirstResult($pageOffset)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }
    public function getTotalDetteClients(): int
    {
        return (int) $this->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    public function findOneBySomeField($value): ?Dette
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
