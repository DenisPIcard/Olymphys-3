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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Equipesadmin;
use App\Entity\Edition;
use App\Entity\Elevesinter;
use App\Form\Filter\ElevesinterFilterType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

class ElevesciaController extends EasyAdminController
{    public function __construct(SessionInterface $session)
        {
            $this->session = $session;
            
        }
    
    protected function createFiltersForm(string $entityName): FormInterface
    { 
        $form = parent::createFiltersForm($entityName);
        
        $form->add('edition', ElevesinterFilterType::class, [
            'class' => Edition::class,
            'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                    ->orderBy('u.ed', 'DESC');
                                     },
           'choice_label' => 'getEd',
            'multiple'=>false,]);
            $form->add('equipe', ElevesinterFilterType::class, [
                         'class' => Equipesadmin::class,
                         'query_builder' => function (EntityRepository $er) {
                                         return $er->createQueryBuilder('u')
                                                        ->addOrderBy('u.edition','DESC')
                                                         ->addOrderBy('u.numero', 'ASC');

                                                  },
                        'choice_label' => function($equipe){return $equipe->getInfoequipe();},
                         'multiple'=>false,]);
           
        return $form;
    }
   
    public  function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null){
           $repositoryEdition = $this->getDoctrine()->getRepository('App:Edition');
            $edition= $this->session->get('edition');
                 // $edition=$repositoryEdition->findOneBy([], ['id' => 'desc']);
            $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
        /* @var DoctrineQueryBuilder */
        $queryBuilder = $em->createQueryBuilder()
            ->select('entity')
            ->from($this->entity['class'], 'entity')
            ->leftJoin('entity.equipe','equipe')
            ->where('equipe.edition =:edition')
            ->setParameter('edition', $edition)
           ->orderBy('equipe.numero', 'ASC');
            return $queryBuilder;
         
      }
    
    
    
}

