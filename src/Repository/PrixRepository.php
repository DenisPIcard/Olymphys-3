<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
/**
 * PrixRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PrixRepository extends \Doctrine\ORM\EntityRepository
{
	public function getNbrePrix($niveau)
	{
	    $qb = $this->createQueryBuilder('p');
	    $qb->select('count(p.id)');
	    $qb->where('p.classement = :classement');
	    $qb->setParameter('classement', $niveau);

	    return $qb->getQuery()->getSingleScalarResult();
	}

                public static function getListPrix(PrixRepository $pr): QueryBuilder
                {   
		
                    return $pr->createQueryBuilder('p')->select('p');
                          //->where('p.classement = :classement')
                          //->setParameter('classement',$classement);    
                    
 
                     }

}                