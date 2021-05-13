<?php
namespace App\Controller\Admin;
use App\Form\Filter\ElevesinterFilterType;
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
use App\Entity\User;
use App\Form\Filter\EquipesadminFilterType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

class ProfesseursController extends EasyAdminController
{    private $session;
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
                                       'multiple'=>false,
                                       'mapped'=>false,
            ]);


        return $form;
    }
   
    public  function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null){
        $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
        $qb =$em->createQueryBuilder()
            ->select('entity')
            ->from('App:Equipeadmin', 'entity')
            ->groupBy('entity.idProf')
            ->where('entity.edition =:edition')
            ->setParameter('edition',$this->session->get('edition'));
        $listeEquipes=$qb->getQuery()->getResult();
        $i=0;
        foreach($listeEquipes as $equipe){
            $listeProfs[$i]=$equipe->getIdProf1()->getId();
                if ($equipe->getIdProf1()!=null){
                    $listeProfs[$i+1]=$equipe->getIdProf2()->getId();
                }
            $i++;

        }


        $qb1 =$em->createQueryBuilder()
            ->select('entity')
            ->from($this->entity['class'], 'entity')
            ->groupBy('entity.id')
            ->where('entity.id in listeprofid')
            ->setParameter('listeprofid',$listeProfs)
            ->addOrderBy('entity.nomProf1','ASC');
       //dd($qb1);
        $listeProfs=$qb1->getQuery()->getResult();
        //dd($listeProfs);

        return $qb1;

         
      }
    

}

