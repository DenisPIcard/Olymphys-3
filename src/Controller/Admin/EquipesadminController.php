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
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

class EquipesadminController extends EasyAdminController
{   
    
    /*protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
                {
    /* @var EntityManager */
   /* $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);

    /* @var QueryBuilder */
    /*$queryBuilder = $em->createQueryBuilder()
        ->select('entity')
        ->from($this->entity['class'], 'entity')
        ;

    if (!empty($dqlFilter)) {
        $queryBuilder->andWhere($dqlFilter);
    }

    $queryBuilder->addOrderBy('entity.centre', 'ASC');
    $queryBuilder->addOrderBy('entity.edition', 'DESC');

    return $queryBuilder;
}*/
   
     

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
           
        
        $repositoryEdition = $this->getDoctrine()->getRepository('App:Edition');
                  $edition=$repositoryEdition->findOneBy([], ['id' => 'desc']);
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
    
  
    
}

