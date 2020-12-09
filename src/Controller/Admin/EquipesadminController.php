<?php
namespace App\Controller\Admin;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType ; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use App\Entity\Equipesadmin;
use App\Entity\Edition;
use App\Entity\Centrescia;
use App\Entity\Elevesinter;
use App\Entity\Eleves;
use App\Entity\Equipes;
use App\Entity\Fichiersequipes;
use App\Form\Filter\EquipesadminFilterType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

class EquipesadminController extends EasyAdminController
{   
    public function __construct(SessionInterface $session)
        {
            $this->session = $session;
            
        }
   
     

    protected function createFiltersForm(string $entityName): FormInterface
    { 
        $form = parent::createFiltersForm($entityName);
        
        $form->add('edition', EquipesadminFilterType::class, [
            'class' => Edition::class,
            'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                    ->addOrderBy('u.ed', 'DESC');
                                     },
           'choice_label' => 'getEd',
            'multiple'=>false,]);
            $form->add('centre', EquipesadminFilterType::class, [
                         'class' => Centrescia::class,
                         'query_builder' => function (EntityRepository $er) {
                                         return $er->createQueryBuilder('u')
                                                 ->addOrderBy('u.centre', 'ASC');

                                                  },
                        'choice_label' => function($centre){return $centre->getCentre();},
                         'multiple'=>false,]);
           
        return $form;
    }
    public function persistEntity($entity)
    {
        
        $repositoryEdition = $this->getDoctrine()->getRepository('App:Edition');
                  $edition=$repositoryEdition->findOneBy([], ['id' => 'desc']);
                  $entity->setEdition($edition);
        
         parent::persistEntity($entity);
        
    }
    public  function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null){
           $request=Request::createFromGlobals();
        
        $edition= $this->session->get('edition');
         $this->session->set('edition_titre',$edition->getEd());
            $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
      if ($request->query->get('entity')=='Equipesadmin'){
        /* @var DoctrineQueryBuilder */
        $queryBuilder = $em->createQueryBuilder()
            ->select('entity')
            ->from($this->entity['class'], 'entity')
            ->leftJoin('entity.edition','edition')
            ->where('edition.ed =:edition')
            ->setParameter('edition', $edition->getEd())
           ->addOrderBy('entity.centre', 'ASC')
           ->addOrderBy('entity.'.$sortField,$sortDirection);}
         if ($request->query->get('entity')=='Selectionnees'){
             $queryBuilder = $em->createQueryBuilder()
            ->select('entity')
            ->from($this->entity['class'], 'entity')
            ->where('entity.edition =:edition')
            ->setParameter('edition', $edition)
            ->andWhere('entity.selectionnee = TRUE')
           ->addOrderBy('entity.lettre', 'ASC');
            
         }
           
           
           
            return $queryBuilder;
         
      }
    public function deleteAction(){
         $class = $this->entity['class'];
       $id = $this->request->query->get('id');
           $em=$this->getDoctrine()->getManager();   
        $repository = $this->getDoctrine()->getRepository($class);
        $equipe=$repository->find(['id'=>$id]);
       
         $repositoryElevesinter = $this->getDoctrine()->getRepository(Elevesinter::class);
         $repositoryEleves = $this->getDoctrine()->getRepository(Eleves::class);
         $repositoryFichiers = $this->getDoctrine()->getRepository(Fichiersequipes::class);
         $repositoryEquipes = $this->getDoctrine()->getRepository(Equipes::class);
         $qb= $repositoryFichiers->createQueryBuilder('f')
                 ->where('f.equipe =:equipe')
                 ->setParameter('equipe',$equipe);
        $liste_fichiers=$qb->getQuery()->getResult();
        
        foreach($liste_fichiers as $fichier){
            $fichier->setEquipe(null);
            $fichier->setProf(null);
            $fichier->setEleve(null);
         $em->remove($fichier);
        }
         $qb2= $repositoryElevesinter->createQueryBuilder('e')
                 ->andWhere('e.equipe =:equipe')
                 ->setParameter('equipe',$equipe);
        $liste_elevesinter=$qb2->getQuery()->getResult();
        
        $liste_eleves=$repositoryEleves->createQueryBuilder('e')
                 ->andWhere('e.equipe =:equipe')
                 ->setParameter('equipe',$equipe)
                ->getQuery()->getResult();
        $equipe=$repositoryEquipes->createQueryBuilder('e')
                 ->andWhere('e.infoequipe =:equipe')
                 ->setParameter('equipe',$equipe)
                ->getQuery()->getSingleResult();
       If ($equipe){
        $equipe->setInfoequipe(null);
       }
         foreach($liste_elevesinter as $eleve){
            $eleve->setEquipe(null);
            $eleve->setAutorisationphotos(null);
             $em->remove($eleve);      
        }
         foreach($liste_eleves as $eleve){
            $eleve->setEquipe(null);
           
             $em->remove($eleve);      
        }
        
        
        
        
        $em->flush();
        
        
        
        
        
        return parent::deleteAction(); 
    }
  
    
}

