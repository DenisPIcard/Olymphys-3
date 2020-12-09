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
use App\Form\Filter\EquipesFilterType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

class EquipesController extends EasyAdminController
{   
    public function __construct(SessionInterface $session)
        {
            $this->session = $session;
            
        }
   
     

    protected function createFiltersForm(string $entityName): FormInterface
    { 
        $form = parent::createFiltersForm($entityName);
        
        $form->add('edition', EquipesFilterType::class, [
            'class' => Edition::class,
            'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('e')
                                           ->addOrderBy('e.ed', 'DESC');
                                     },
           'choice_label' => 'getEd',
            'multiple'=>false,
            'mapped'=>false]);
            
           
        return $form;
    }
   
        
  
    public  function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null){
           $request=Request::createFromGlobals();
        
        $edition= $this->session->get('edition');
         $this->session->set('edition_titre',$edition->getEd());
            $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
    
        /* @var DoctrineQueryBuilder */
        $queryBuilder = $em->createQueryBuilder()
            ->select('entity')
            ->from($this->entity['class'], 'entity')
            ->leftJoin('entity.infoequipe','e')
            ->where('e.edition =:edition')
            ->setParameter('edition', $this->session->get('edition'))
           ->addOrderBy('entity.lettre', 'ASC')
           ->addOrderBy('entity.'.$sortField,$sortDirection);
        
     
        
    
           
           
           
            return $queryBuilder;
         
      }
  
  
    
}

