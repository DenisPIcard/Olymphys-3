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
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
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
        
            if($entityName=='Fichiersequipesmemoiresinter'){                         
            $form->add('centre', FichiersequipesFilterType::class, [
                         'class' => Centrescia::class,
                         'query_builder' => function (EntityRepository $er) {
                                         return $er->createQueryBuilder('u')
                                                 ->addOrderBy('u.centre','ASC');
                                                      
                                                  },
                        'choice_label' => 'getCentre',
                         'multiple'=>false,]);
            
                                    
            $form->add('equipe', FichiersequipesFilterType::class, [
                         'class' => Equipesadmin::class,
                         'query_builder' => function (EntityRepository $er) {
                                         return $er->createQueryBuilder('u')
                                                 ->andWhere('u.selectionnee =:selectionnee')
                                                 ->setParameter('selectionnee','FALSE')
                                                 ->addOrderBy('u.centre','ASC')
                                                 ->addOrderBy('u.numero','ASC');     
                                                  },
                        'choice_label' => 'getInfoequipe',
                         'multiple'=>false,]);
            }
            
             if($entityName=='Fichiersequipesmemoirescn'){                         
                      
                                    
            $form->add('equipe', FichiersequipesFilterType::class, [
                         'class' => Equipesadmin::class,
                         'query_builder' => function (EntityRepository $er) {
                                         return $er->createQueryBuilder('u')
                                                 ->andWhere('u.selectionnee =:selectionnee')
                                                 ->setParameter('selectionnee',TRUE)
                                                 ->addOrderBy('u.edition','DESC')
                                                 ->addOrderBy('u.lettre','ASC');
                                                      
                                                  },
                        'choice_label' => 'getInfoequipenat',
                         'multiple'=>false,]);
                                                  
            }
            //$form->add('submit', SubmitType::class, [ 'label' => 'Appliquer',  ]);
         
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
          
       
        $edition= $this->session->get('edition');
         $this->session->set('edition_titre',$edition->getEd());
            $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
        /* @var DoctrineQueryBuilder */
        $queryBuilder = $em->createQueryBuilder('l')
            ->select('entity')
            ->from($this->entity['class'], 'entity')
           ->join('entity.equipe','eq')
           ->andWhere('entity.edition =:edition')
           // ->andWhere('eq.edition =:edition')
            ->setParameter('edition', $edition);
        
        if (!empty($dqlFilter)) {
           
              if ($dqlFilter=='entity.typefichier < 2  AND  entity.national = 1'){
              $queryBuilder->andWhere('entity.typefichier < 2')
                      -> andWhere('entity.national = TRUE')
                       ->addOrderBy('eq.lettre', 'ASC');;
              }
              if ($dqlFilter=='entity.typefichier < 2 AND  entity.national = false'){
              $queryBuilder->andWhere('entity.typefichier < 2')
                      ->andWhere('entity.national = FALSE')
                       ->addOrderBy('eq.centre', 'ASC')
                        ->addOrderBy('eq.numero', 'ASC');;
                       
              }
               if ($dqlFilter=='entity.typefichier = 4'){
              $queryBuilder->andWhere($dqlFilter)
                       ->addOrderBy('eq.numero', 'ASC')
                      ->addOrderBy('eq.lettre', 'ASC');;;;
              }
              
               if ($dqlFilter=='entity.typefichier = 2'){
              $queryBuilder->andWhere($dqlFilter)
                       ->addOrderBy('eq.numero', 'ASC')
                      ->addOrderBy('eq.lettre', 'ASC');;;;
              }
               if ($dqlFilter=='entity.typefichier = 3 AND national = 1'){
                   
              $queryBuilder->andWhere('entity.typefichier = 3')
                      -> andWhere('entity.national = TRUE')
                       ->addOrderBy('eq.numero', 'ASC')
                      ->addOrderBy('eq.lettre', 'ASC');;;;
              }  
               if ($dqlFilter=='entity.typefichier = 5'){
              $queryBuilder->andWhere($dqlFilter)
                       ->addOrderBy('eq.numero', 'ASC')
                       ->addOrderBy('eq.lettre', 'ASC');;;;
              }
              }
      
            return $queryBuilder;
         
      }
     public function LireAction()
     {    $fichier='';
          $class = $this->entity['class'];
         $repository = $this->getDoctrine()->getRepository($class);
         $id = $this->request->query->get('id');
         $entity = $repository->find($id);
        
         
             
             if(($entity->getTypefichier() ==0) or ($entity->getTypefichier() ==1)  ){
                 
                 $fichier= $this->getParameter('app.path.fichiers').'/'.$this->getParameter('type_fichier')[0].'/'.$entity->getFichier();
             }
             else{
                 $fichier= $this->getParameter('app.path.fichiers').'/'.$this->getParameter('type_fichier')[$entity->getTypefichier()].'/'.$entity->getFichier();
             }
               
                 $file=new File($fichier);
                    $response = new BinaryFileResponse($fichier);
         
                    $disposition = HeaderUtils::makeDisposition(
                      HeaderUtils::DISPOSITION_ATTACHMENT,

                     $entity->getFichier()
                            );
                    $response->headers->set('Content-Type', $file->guessExtension()); 
                    $response->headers->set('Content-Disposition', $disposition);
        
                  return $response; 
               
                  
     }
     
      public function eraseBatchAction(array $ids)
    {
        $class = $this->entity['class'];
        $em=$this->getDoctrine()->getManager();
        
        if ($class=='App\Entity\Fichiersequipes') { 
            $repository = $this->getDoctrine()->getRepository($class);
        
        foreach($ids as $id)
        
        {
            $fichier=$repository->find($id);
            if ($fichier){
                $fichier->setEquipe(null);
                $fichier->setEdition(null);
                $em->remove($fichier);
                $em->flush();
                
                
                
            }
            
        }  
            
         }
    }
    
}

