<?php

namespace App\Repository;
use App\Entity\Edition;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{private $edition;
    public function __construct(ManagerRegistry $registry, SessionInterface $session)
    {    
          parent::__construct($registry, User::class);
         $this->session = $session;
         
    }
   public function getProfautorisation(UserRepository $er): QueryBuilder//Liste des prof sans autorisation photos
     {   
          $roles= ['ROLE_PROF','ROLE_USER'];
      
         
         $qb=$er->createQueryBuilder('p');
          $qb1 =$er->createQueryBuilder('u')
                  ->Where('u.autorisationphotos is null')
                 ->andWhere($qb->expr()->like('u.roles',':roles'))
                  ->setParameter('roles','a:2:{i:0;s:9:"ROLE_PROF";i:1;s:9:"ROLE_USER";}')
                  ->addOrderBy('u.nom','ASC');
      
          return $qb1;
     }               
    public function getProfesseur(UserRepository $er): QueryBuilder//Liste des prof sans autorisation photos
     {   
         
         $qb=$er->createQueryBuilder('p');
          $qb1 =$er->createQueryBuilder('u')
                  ->andWhere($qb->expr()->like('u.roles',':roles'))
                  ->setParameter('roles','%i:0;s:9:"ROLE_PROF";i:2;s:9:"ROLE_USER";%')
                  ->addOrderBy('u.nom','ASC');
       //dd($qb1);
          return $qb1;
     }            
}
