<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }
    // src/Repository/CommandeRepository.php
// src/Repository/CommandeRepository.php

// src/Repository/CommandeRepository.php

public function findAllWithDetails()
{
    return $this->createQueryBuilder('c')
        ->select('c.id', 'c.createdAt', 'u.email as user_email', 'SUM(cp.prix * cp.quantite) as total')
        ->join('c.user', 'u')
        ->leftJoin('c.commandeProduits', 'cp')
        ->groupBy('c.id')
        ->getQuery()
        ->getResult();
}
//    /**
//     * @return Commande[] Returns an array of Commande objects
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

//    public function findOneBySomeField($value): ?Commande
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
