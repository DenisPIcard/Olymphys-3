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
use App\Entity\User;
use App\Form\Filter\ProfesseursFilterType;
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
        
        $form->add('edition', ProfesseursFilterType::class, [
            'class' => User::class,
            'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                    ->orderBy('u.ed', 'DESC');
                                     },
           'choice_label' => 'getNomPrenom',
           'multiple'=>false,]);
            $form->add('equipe', ProfesseursFilterType::class, [
                         'class' => Equipesadmin::class,
                         'query_builder' => 'getEquipes',

                        'choice_label' => function($equipe){return $equipe->getInfoequipe();},
                         'multiple'=>false,]);
           
        return $form;
    }
   
    public  function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null){
           $repositoryEquipes = $this->getDoctrine()->getRepository('App:Equipesadmin');
           $repositoryUser = $this->getDoctrine()->getRepository('App:User');
           $edition= $this->session->get('edition');
                 // $edition=$repositoryEdition->findOneBy([], ['id' => 'desc']);
            $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
            $equipes = $repositoryEquipes->createQueryBuilder('e')
                                         ->andWhere('e.edition =:edition')
                                         ->setParameter('edition',$edition)
                                         ->getQuery()->getArrayResult();
            $profs= $repositoryUser->createQueryBuilder('p')
                                   ->where('roles =:roles')
                                    ->setParameter('roles', 'a:2:{i:0;s:9:"ROLE_PROF";i:1;s:9:"ROLE_USER";}')
                                    ->getQuery()->getResult();

            /* @var DoctrineQueryBuilder */
            $queryBuilder = $em->createQueryBuilder()
                ->select('entity')
                ->from($this->entity['class'], 'entity')
                ->where('entity.equipes =:equipes')
                ->setParameter('equipes', $equipes)
                ->orderBy('entity.nom', 'ASC');
            return $queryBuilder;
         
      }
    
    public function extract_tableau_excel_ProfsAction(){
        $repositoryEdition = $this->getDoctrine()->getRepository('App:Elevesinter');
            $edition= $this->session->get('edition');
                 // $edition=$repositoryEdition->findOneBy([], ['id' => 'desc']);
            $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
        /* @var DoctrineQueryBuilder */
        $queryBuilder = $em->createQueryBuilder()
                 ->select('entity')
                -> from($this->entity['class'], 'entity')
                ->leftJoin('entity.equipe','e')
                ->andWhere('e.selectionnee = FALSE')
                ->andWhere('e.edition =:edition')
                ->setParameter('edition',$edition);
        $liste_profs = $queryBuilder->getQuery()->getResult();
             
        
        $spreadsheet = new Spreadsheet();
         $spreadsheet->getProperties()
                        ->setCreator("Olymphys")
                        ->setLastModifiedBy("Olymphys")
                        ->setTitle("CIA  ".$edition->getEd()."ème édition - élèves non sélectionnés")
                        ->setSubject("Elèves non sélectionnés")
                        ->setDescription("Office 2007 XLSX Document pour mailing diplomes participation ")
                        ->setKeywords("Office 2007 XLSX")
                        ->setCategory("Test result file");
 
                $sheet = $spreadsheet->getActiveSheet();
 
               
           
       
                $ligne=1;

                $sheet->setCellValue('A'.$ligne, 'Nom')
                    ->setCellValue('B'.$ligne, 'Prenom')
                    ->setCellValue('C'.$ligne, 'email')
                    ->setCellValue('D'.$ligne, 'rne')
                    ->setCellValue('E'.$ligne, 'Lycée')
                    ->setCellValue('F'.$ligne, 'Commune')
                    ->setCellValue('G'.$ligne, 'Courriel');
                
                $ligne +=1; 

        	foreach ($liste_profs as $prof)
                {

                 
                    $sheet->setCellValue('A'.$ligne,$prof->getNom() )
                        ->setCellValue('B'.$ligne, $prof->getPrenom())
                        ->setCellValue('C'.$ligne, $prof->getEmail())
                        ->setCellValue('D'.$ligne, $prof->getRne())
                        ->setCellValue('E'.$ligne,$prof->getRneId()->getNom())
                        ->setCellValue('F'.$ligne, $prof->getRneId()->getCommune())
                        ->setCellValue('G'.$ligne, $prof->getadresse());
                      $ligne +=1;
                }
                    
 

 
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="eleves_non_sélectionnés.xls"');
                header('Cache-Control: max-age=0');
        
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                ob_end_clean();
                $writer->save('php://output');
        
        
        
        
        
    }
    public function extract_tableau_excel_Eleves_sBatchAction(){
        $repositoryEdition = $this->getDoctrine()->getRepository('App:Elevesinter');
        $repositoryEquipescn = $this->getDoctrine()->getRepository('App:Equipes');
            $edition= $this->session->get('edition');
                 // $edition=$repositoryEdition->findOneBy([], ['id' => 'desc']);
            $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
        /* @var DoctrineQueryBuilder */
        $queryBuilder = $em->createQueryBuilder()
                 ->select('entity')
                -> from($this->entity['class'], 'entity')
                ->leftJoin('entity.equipe','e')
                ->andWhere('e.selectionnee = TRUE')
                ->andWhere('e.edition =:edition')
                ->setParameter('edition',$edition);
        $liste_eleves = $queryBuilder->getQuery()->getResult();
             
        
        $spreadsheet = new Spreadsheet();
         $spreadsheet->getProperties()
                        ->setCreator("Olymphys")
                        ->setLastModifiedBy("Olymphys")
                        ->setTitle("CN   ".$edition->getEd()."ème édition - élèves sélectionnés avec mail")
                        ->setSubject("Elèves  sélectionnés")
                        ->setDescription("Office 2007 XLSX Document pour mailing diplomes participation ")
                        ->setKeywords("Office 2007 XLSX")
                        ->setCategory("Test result file");
 
                $sheet = $spreadsheet->getActiveSheet();
 
               
           
       
                $ligne=1;

                $sheet->setCellValue('A'.$ligne, 'Nom')
                    ->setCellValue('B'.$ligne, 'Prenom')
                    ->setCellValue('C'.$ligne, 'courriel')    
                    ->setCellValue('D'.$ligne, 'Lettre')
                     ->setCellValue('E'.$ligne, 'Titre')  
                    ->setCellValue('F'.$ligne, 'Lycée')
                   ->setCellValue('G'.$ligne, 'Commune')
                   ->setCellValue('G'.$ligne, 'prix');
                   
                
                $ligne +=1; 

        	foreach ($liste_eleves as $eleve) 
                {
                    $equipe=$eleve->getEquipe();
                    $equipecn=$repositoryEquipescn->createQueryBuilder('e')
                                                                         ->where('e.infoequipe =:equipe')
                                                                         ->setParameter('equipe',$equipe)
                                                                         ->getQuery()->getSingleResult();
                   
                    $sheet->setCellValue('A'.$ligne,$eleve->getNom() )
                        ->setCellValue('B'.$ligne, $eleve->getPrenom())
                        ->setCellValue('C'.$ligne, $eleve->getCourriel())    
                        ->setCellValue('D'.$ligne, $equipe->getLettre())
                        ->setCellValue('E'.$ligne, $equipe->getTitreProjet())
                        ->setCellValue('F'.$ligne,$equipe->getRneId()->getNom())
                        ->setCellValue('G'.$ligne, $equipe->getRneId()->getCommune())
                        ->setCellValue('H'.$ligne, $equipecn->getPrix()->getClassement())
                        ->setCellValue('I'.$ligne, $equipecn->getPrix()->getPrix())
                        ->setCellValue('J'.$ligne, $equipecn->getPhrases()->getPrix());
                      $ligne +=1;
                }
                    
 

 
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="eleves_sélectionnés.xls"');
                header('Cache-Control: max-age=0');
        
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                ob_end_clean();
                $writer->save('php://output');
        
        
        
        
        
    }
}

