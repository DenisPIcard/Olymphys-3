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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
    
    public function extract_tableau_excel_ElevesnsBatchAction(){
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
        $liste_eleves = $queryBuilder->getQuery()->getResult();
             
        
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
                    ->setCellValue('C'.$ligne, 'Numequipe')
                     ->setCellValue('D'.$ligne, 'Titre')  
                    ->setCellValue('E'.$ligne, 'Lycée')
                   ->setCellValue('F'.$ligne, 'Commune');
                   
                
                $ligne +=1; 

        	foreach ($liste_eleves as $eleve) 
                {
                    $equipe=$eleve->getEquipe();
                 
                    $sheet->setCellValue('A'.$ligne,$eleve->getNom() )
                        ->setCellValue('B'.$ligne, $eleve->getPrenom())
                        ->setCellValue('C'.$ligne, $equipe->getNumero())
                        ->setCellValue('D'.$ligne, $equipe->getTitreProjet())
                        ->setCellValue('E'.$ligne,$equipe->getRneId()->getNom())
                        ->setCellValue('F'.$ligne, $equipe->getRneId()->getCommune());
                      $ligne +=1;
                }
                    
 

 
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="eleves_non_sélectionnés.xls"');
                header('Cache-Control: max-age=0');
        
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                $writer->save('php://output');
        
        
        
        
        
    }
    public function extract_tableau_excel_Eleves_sBatchAction(){
        $repositoryEdition = $this->getDoctrine()->getRepository('App:Elevesinter');
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
                        ->setSubject("Elèves non sélectionnés")
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
                   ->setCellValue('G'.$ligne, 'Commune');
                   
                
                $ligne +=1; 

        	foreach ($liste_eleves as $eleve) 
                {
                    $equipe=$eleve->getEquipe();
                 
                    $sheet->setCellValue('A'.$ligne,$eleve->getNom() )
                        ->setCellValue('B'.$ligne, $eleve->getPrenom())
                        ->setCellValue('C'.$ligne, $eleve->getCourriel())    
                        ->setCellValue('D'.$ligne, $equipe->getLettre())
                        ->setCellValue('E'.$ligne, $equipe->getTitreProjet())
                        ->setCellValue('F'.$ligne,$equipe->getRneId()->getNom())
                        ->setCellValue('G'.$ligne, $equipe->getRneId()->getCommune());
                      $ligne +=1;
                }
                    
 

 
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="eleves_sélectionnés.xls"');
                header('Cache-Control: max-age=0');
        
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                $writer->save('php://output');
        
        
        
        
        
    }
}

