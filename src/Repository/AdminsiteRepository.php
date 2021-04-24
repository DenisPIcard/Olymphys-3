<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Adminsite;
/**
 * CadeauxRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below. Copier coller de visites 
 */
class AdminsiteRepository extends ServiceEntityRepository
{
    
        public function __construct(ManagerRegistry $registry)
                    {
                        parent::__construct($registry, Adminsite::class);
                    }
    
    
    public function getSession(AdminsiteRepository $ar): QueryBuilder
                {   
		
                    return $ar ->createQueryBuilder('s')->select('s');
    
                }
    public function getMaxId()
             {
                            $qb = $this->createQueryBuilder('s')->select('s')
                                             ->andWhere('s.id = : idmax')
                                             ->setParameter('idmax', LAST_INSERT_ID);
                            return $qb->getQuery()->getSingleResult();
                 }
    
    
}

