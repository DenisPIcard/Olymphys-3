<?php

namespace App\Repository;

use App\Entity\Livredoreleves;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Livredoreleves|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livredoreleves|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livredoreleves[]    findAll()
 * @method Livredoreleves[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivredorelevesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livredoreleves::class);
    }

    // /**
    //  * @return Livredoreleves[] Returns an array of Livredoreleves objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livredoreleves
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
