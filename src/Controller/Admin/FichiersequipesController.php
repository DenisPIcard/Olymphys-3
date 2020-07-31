<?php
namespace App\Controller\Admin;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType ; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use App\Entity\Equipesadmin;
use App\Entity\Edition;
use App\Entity\Centrescia;
use App\Form\Filter\EquipesadminFilterType;
use App\Form\Filter\FichiersequipesFilterType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

class FichiersequipesController extends EasyAdminController
{   public function __construct(SessionInterface $session)
        {
            $this->session = $session;
            
        }
    
    
    
    
    protected function createFiltersForm(string $entityName): FormInterface
    { 
        $form = parent::createFiltersForm($entityName);
        
        $form->add('edition', FichiersequipesFilterType::class, [
            'class' => Edition::class,
            'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                    ->addOrderBy('u.ed', 'DESC');
                                     },
           'choice_label' => 'getEd',
            'multiple'=>false,]);
            $form->add('centre', FichiersequipesFilterType::class, [
                         'class' => Centrescia::class,
                         'query_builder' => function (EntityRepository $er) {
                                         return $er->createQueryBuilder('u')
                                                 ->addOrderBy('u.centre','ASC');
                                                      
                                                  },
                        'choice_label' => 'getCentre',
                         'multiple'=>false,]);
           
        return $form;
    }
    public function persistEntity($entity)
    {  $em=$this->getDoctrine()->getManager();
       
           if ($this->session->get('concours')=='interacadémique')
           {
               $entity->setNational(0);
           }
           if ($this->session->get('concours')=='national')
           {
               $entity->setNational(1);
           }
         $Fichiersrepository=$this->getDoctrine()
		->getManager()
		->getRepository('App:Fichiersequipes');
          $Fichiers=$Fichiersrepository->findByEquipe(['equipe'=>$entity->getEquipe()]);
          
          
          $qb=$Fichiersrepository->createQueryBuilder('f')
                  ->andWhere('f.equipe =:equipe')
                  ->setParameter('equipe', $entity->getEquipe())
                  ->andWhere('f.typefichier =:typefichier')
                  ->setParameter('typefichier',$entity->getTypefichier() );
          try {
          $fichier = $qb->getQuery()->getSingleResult();
                    }
          catch(\Exception $e) {
              
          }          
          if (isset($fichier)){
              $fichier->setNational($entity->getNational());
              $fichier->setFichierFile($entity->getFichierFile());
              $em->persist($fichier);
              $em->flush();
          }
         else{
            $edition=$this->session->get('edition');
            $edition=$em->merge($edition);
            
                  $entity->setEdition($edition);
                  //dd($entity);
                   parent::persistEntity($entity);
         }
    }
    public  function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null){
          
        
        $repositoryEdition = $this->getDoctrine()->getRepository('App:Edition');
                  $edition=$repositoryEdition->findOneBy([], ['id' => 'desc']);
            $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
        /* @var DoctrineQueryBuilder */
        $queryBuilder = $em->createQueryBuilder('l')
            ->select('entity')
            ->from($this->entity['class'], 'entity')
           ->join('entity.equipe','eq')
           ->addOrderBy('eq.numero', 'ASC')
            ->andWhere('entity.edition =:edition')
           // ->andWhere('eq.edition =:edition')
            ->setParameter('edition', $edition)
         ->addOrderBy('eq.centre', 'ASC');
        
          if (!empty($dqlFilter)) {
              $queryBuilder->andWhere($dqlFilter);
                                          
              }
           
        
        
        //
        
            return $queryBuilder;
         
      }
    
    
    
}

