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
           
        
        $edition= $this->session->get('edition');
         $this->session->set('edition_titre',$edition->getEd());
            $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
        /* @var DoctrineQueryBuilder */
        $queryBuilder = $em->createQueryBuilder()
            ->select('entity')
            ->from($this->entity['class'], 'entity')
            ->leftJoin('entity.edition','edition')
            ->where('edition.ed =:edition')
            ->setParameter('edition', $edition->getEd())
           ->addOrderBy('entity.centre', 'ASC')
           ->addOrderBy('entity.'.$sortField,$sortDirection);
            return $queryBuilder;
         
      }
    public function deleteAction(){
         $class = $this->entity['class'];
       $id = $this->request->query->get('id');
           $em=$this->getDoctrine()->getManager();   
        $repository = $this->getDoctrine()->getRepository($class);
        $equipe=$repository->find(['id'=>$id]);
       
         $repositoryEleves = $this->getDoctrine()->getRepository(Elevesinter::class);
         $repositoryFichiers = $this->getDoctrine()->getRepository(Fichiersequipes::class);
         
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
         $qb2= $repositoryEleves->createQueryBuilder('e')
                 ->andWhere('e.equipe =:equipe')
                 ->setParameter('equipe',$equipe);
        $liste_eleves=$qb2->getQuery()->getResult();
        
         foreach($liste_eleves as $eleve){
            $eleve->setEquipe(null);
            $eleve->setAutorisationphotos(null);
             $em->remove($eleve);      
        }
        $em->flush();
        
        
        
        
        
        return parent::deleteAction(); 
    }
  
    
}

