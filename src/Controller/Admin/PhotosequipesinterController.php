<?php
namespace App\Controller\Admin;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType ; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use App\Entity\Equipesadmin;
use App\Entity\Edition;
use App\Entity\Centrescia;
use App\Entity\Photos;
use EasyCorp\Bundle\EasyAdminBundle\Mapping\Annotation\Entity;
use App\Form\Filter\PhotosequipesinterFilterType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

class PhotosequipesinterController extends EasyAdminController
{   
    protected function createFiltersForm(string $entityName): FormInterface
    { 
        $form = parent::createFiltersForm($entityName);
        
        $form->add('edition', PhotosequipesinterFilterType::class, [
            'class' => Edition::class,
            'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                    ->orderBy('u.ed', 'DESC');
                                     },
           'choice_label' => 'getEd',
            'multiple'=>false,]);
            $form->add('centre', PhotosequipesinterFilterType::class, [
                         'class' => Centrescia::class,
                         'query_builder' => function (EntityRepository $er) {
                                         return $er->createQueryBuilder('u')
                                                 ->orderBy('u.centre','ASC');
                                                  },
                        'choice_label' => 'getCentre',
                         'multiple'=>false,]);
           $form->add('equipe', PhotosequipesinterFilterType::class, [
                               'class' => Equipesadmin::class,
                               'query_builder' => function (EntityRepository $er) {
                                         return $er->createQueryBuilder('u')
                                                 ->addOrderBy('u.numero','ASC')
                                                 ->addOrderBy('u.edition', 'DESC');
                                                  },
                           'choice_label'=>'getInfoEquipe',
                         'multiple'=>true,]);
                                             
        return $form;
    }
    public function persistEntity($entity)
    {
                  $entity->setNational(False);
        
         parent::persistEntity($entity);
        
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
             ->andWhere('entity.edition =:edition')
            ->setParameter('edition', $edition)  
            ->andWhere('entity.national =:national')
            ->setParameter('national', 'FALSE')  
            ->addOrderBy('eq.centre', 'ASC')
            ->addOrderBy('eq.numero', 'ASC');
           
           
          if (!empty($dqlFilter)) {
              $queryBuilder->andWhere($dqlFilter);
                                          
              }
           
            return $queryBuilder;
         
      }
public function EnregistrerAction() {
 
         
         $repository = $this->getDoctrine()->getRepository(Photos::class);
         $id = $this->request->query->get('id');
         $entity = $repository->find($id);
         $fichier=$this->getParameter('app.path.photos').'/'.$entity->getPhoto();
         $application= 'image/jpeg';
         $name=$entity->getPhoto();
         $response = new BinaryFileResponse($fichier);
         
         $disposition = HeaderUtils::makeDisposition(
           HeaderUtils::DISPOSITION_ATTACHMENT,
                 
           $name
                 );
         $response->headers->set('Content-Type', $application); 
         $response->headers->set('Content-Disposition', $disposition);
         
        
         //$content = $this->render('secretariat\lire_memoire.html.twig', array('repertoirememoire' => $this->getParameter('repertoire_memoire_national'),'memoire'=>$fichier));
         return $response; 
        
        
    }
    
    
}

