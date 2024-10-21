<?php

namespace App\Repository;

use App\Dto\ClientSearchDto;
use App\Entity\Client;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    // ClientRepository.php

    public function paginateClients(int $page = 1, int $limit = 2): array
    {
        $pageOffset = ($page - 1) * $limit;

        $query = $this->createQueryBuilder('c')
            ->leftJoin('c.user', 'u')
            ->leftJoin('c.dettes', 'd')
            ->addSelect('u', 'd')
            ->orderBy('c.id', 'ASC')
            ->setFirstResult($pageOffset)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }



    public function getTotalClients(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findClientBy(ClientSearchDto $clientSearchDto, int $page = 1, int $limit = 2): Paginator
    {
        $query = $this->createQueryBuilder('c');
        if (!empty($clientSearchDto->telephone)) {
            $query->andWhere('c.telephone = :telephone')
                ->setParameter('telephone', $clientSearchDto->telephone);
        }
        if (!empty($clientSearchDto->surname)) {
            $query->andWhere('c.surname = :surname')
                ->setParameter('surname', $clientSearchDto->surname);
        }
         $query->orderBy('c.id', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();
            return new Paginator($query);
    }



    //    public function findOneBySomeField($value): ?Client
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
