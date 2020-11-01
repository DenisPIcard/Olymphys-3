<?php

namespace App\Repository;
use App\Entity\Elevesinter;
use App\Entity\Edition;
use App\Repository\EditionRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
/**
 * ElevesinterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below. 
 */
class ElevesinterRepository extends ServiceEntityRepository
{    
    public function __construct(ManagerRegistry $registry, SessionInterface $session)
                    { 
                        parent::__construct($registry, Elevesinter::class);
                        $this->session = $session;
                        
                    }
      
     public function getEleve(ElevesinterRepository $er): QueryBuilder
     {
          
         $edition=$er->session->get('edition');
        
          $qb1 =$er->createQueryBuilder('e')
                  ->where('e.autorisationphotos is null')
                  ->LeftJoin('e.equipe','eq')
            ->andWhere('eq.edition = :edition')
            ->setParameter('edition', $edition)
            ->addOrderBy('eq.numero','ASC');
       
          return $qb1;
     }               

}
