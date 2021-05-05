<?php
namespace App\Controller\Admin;
use App\Entity\Livredor;
use Doctrine\ORM\EntityRepository;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Bridge\Doctrine\Form\Type\EntityType ;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use App\Entity\Equipesadmin;

use App\Entity\Elevesinter;
use App\Entity\Edition;

use App\Entity\User;
use App\Form\Filter\LivredorFilterType;


use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use ZipArchive;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\String\UnicodeString;

class LivredoradminController extends EasyAdminController
{   private $edition;
    public function __construct(SessionInterface $session)
        {
            $this->session = $session;
            
        }
    
    
    
    
    protected function createFiltersForm(string $entityName): FormInterface
    {  

        $form = parent::createFiltersForm($entityName);
       
        $form->add('edition', LivredorFilterType::class, [
            'class' => Edition::class,
            'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                    ->addOrderBy('u.ed', 'DESC');
                                     },
           'choice_label' => 'getEd',
            'multiple'=>false,]);
        return $form;
    
    }

   
    public  function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null){
          
       
        $edition= $this->session->get('edition');
        $this->session->set('edition_titre',$edition->getEd());
        $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
        /* @var DoctrineQueryBuilder */
        $queryBuilder = $em->createQueryBuilder('l')
            ->select('entity')
            ->from($this->entity['class'], 'entity')
            ->andWhere('entity.edition =:edition')
            ->setParameter('edition',$edition);
           

        if (!empty($dqlFilter)) {
           
              if ($dqlFilter=='entity.categorie = eleve'){
              $queryBuilder->andWhere('entity.categorie =:categorie')
                      ->setParameter('categorie','equipe')
                      ->leftJoin('entity.equipe','eq')
                      ->addOrderBy('eq.lettre', 'ASC');;
              }
            if ($dqlFilter=='entity.categorie = profs'){
                $queryBuilder->andWhere('entity.categorie =:categorie')
                    ->setParameter('categorie','prof')
                    ->leftJoin('entity.user','u')
                    ->addOrderBy('u.nom', 'ASC');;
            }
            if ($dqlFilter=='entity.categorie = jury'){
                $queryBuilder->andWhere('entity.categorie =:categorie')
                    ->setParameter('categorie','jury')
                    ->leftJoin('entity.user','u')
                    ->addOrderBy('u.nom', 'ASC');;
            }
            if ($dqlFilter=='entity.categorie = comite'){
                $queryBuilder->andWhere('entity.categorie =:categorie')
                    ->setParameter('categorie','comite')
                    ->leftJoin('entity.user','u')
                    ->addOrderBy('u.nom', 'ASC');;
            }
        } 
            return $queryBuilder;
         
      }

    public function editer(Request $request,$choix) {

        $idedition=explode('-',$choix)[0];
        $type=explode('-',$choix)[1];
        $edition = $repositoryEdition= $this->getDoctrine()
            ->getManager()
            ->getRepository('App:Edition')->findOneById(['id'=>$idedition]);

        $phpWord = new  PhpWord();

        $section = $phpWord->addSection();
        $paragraphStyleName = 'pStyle';
        $phpWord->addParagraphStyle($paragraphStyleName, array( 'align'  => \PhpOffice\PhpWord\Style\Cell::VALIGN_CENTER, 'spaceAfter' => 100));

        $phpWord->addTitleStyle(1, array('bold' => true,  'size'=> 14 ,'spaceAfter' =>240));
        $fontTitre = 'styletitre';
        $phpWord->addFontStyle(
            $fontTitre,
            array('name' => 'Tahoma', 'size' => 12 , 'color' => '0000FF', 'bold' => true, 'align'=>'center')
        );
        //$fontTitre = new \PhpOffice\PhpWord\Style\Font();
        $fontTexte = 'styletexte';
        $phpWord->addFontStyle(
            $fontTexte,
            array('name' => 'Arial', 'size' => 12, 'color' => '000000')
        );

        if (($type=='prof') or($type=='comite')or($type=='jury')){
            $livredor=$this->getDoctrine()
                ->getManager()
                ->getRepository('App:Livredor')->createQueryBuilder('l')
                ->leftJoin('l.user','p')
                ->addOrderBy('p.nom','ASC')
                ->andWhere('l.categorie =:categorie')
                ->setParameter('categorie', $type)
                ->andWhere('l.edition =:edition')
                ->setParameter('edition', $edition)
                ->getQuery()->getResult();

            if ($type=='prof') {
                $equiperepository= $this->getDoctrine()
                    ->getManager()
                    ->getRepository('App:Equipesadmin');
                $section->addText('Livre d\'or des professeurs - Edition '.$this->session->get('edition')->getEd(),  array('bold' => true,  'size'=> 14 ,'spaceAfter' =>240), 'pStyle');
                $section->addTextBreak(3);
                if ($livredor!=null){
                    foreach($livredor as $texte){
                        $prof=$texte->getUser();

                        $equipes=$equiperepository->getEquipes_prof_cn($prof);
                        if (count($equipes)>1){
                            $titreprof =$prof->getNomPrenom().'( équipes ';}
                        else{ $titreprof =$prof->getNomPrenom().'( équipe ';}

                        $i=0;
                        foreach($equipes as $equipe){
                            $titreprof=$titreprof.$equipe->getLettre();
                            if ($i<array_key_last($equipes))
                                $titreprof=$titreprof.', ';
                            $i++;
                        }
                        $titreprof=$titreprof.' )';
                        $section->addText($titreprof,'styletitre');
                        $textlines = explode("\n", $texte->getTexte());

                        $textrun = $section->addTextRun();
                        $textrun->addText(array_shift($textlines), 'styletexte');
                        foreach($textlines as $line) {
                            $textrun->addTextBreak();
                            // maybe twice if you want to seperate the text
                            // $textrun->addTextBreak(2);
                            $textrun->addText($line,null, 'styletexte');
                        }
                        // $section->addText($texte->getTexte(),'styletexte');
                        //$lineStyle = array('weight' => 1, 'width' => 200, 'height' => 0, 'color'=> '0000FF');

                        $section->addTextBreak(3);
                        //$section->addLine($lineStyle);
                        $section->addText('------',null,'pStyle');
                    }}
            }
            if (($type=='comite')or($type=='jury'))  {

                $categorie= $type;;
                $titrepage ='Livre d\'or du '.$categorie.' - Edition '.$this->session->get('edition')->getEd();


                $section->addText($titrepage, array('bold' => true,  'size'=> 14 ,'spaceAfter' =>240) , 'pStyle');
                $section->addTextBreak(3);
                if ($livredor!=null){
                    foreach($livredor as $texte){
                        $titre=$texte->getNom();

                        $section->addText($titre,'styletitre');

                        $textlines = explode("\n", $texte->getTexte());

                        $textrun = $section->addTextRun();
                        $textrun->addText(array_shift($textlines), 'styletexte');
                        foreach($textlines as $line) {
                            $textrun->addTextBreak();
                            // maybe twice if you want to seperate the text
                            // $textrun->addTextBreak(2);
                            $textrun->addText($line, 'styletexte');
                        }

                        $section->addTextBreak(3);
                        //$section->addLine($lineStyle);
                        $section->addText('------',null, 'pStyle');
                    }

                }
            }
        }
        if ($type=='equipe'){
            $livredor=$this->getDoctrine()
                ->getManager()
                ->getRepository('App:Livredor')
                ->createQueryBuilder('e')
                ->where('e.edition =:edition')
                ->setParameter('edition',$edition)
                ->andWhere('e.categorie =:categorie')
                ->setParameter('categorie', $type)
                ->leftJoin('e.equipe','eq')
                ->orderBy('eq.lettre','ASC')
                ->getQuery()->getResult();

            if ($livredor!=null){
                $section->addText('Livre d\'or des élèves- Edition '.$this->session->get('edition')->getEd(),  array('bold' => true,  'size'=> 14 ,'spaceAfter' =>240), 'pStyle');
                $section->addTextBreak(3);
                foreach($livredor as $texte){

                    $equipe= $texte->getEquipe();

                    $titreEquipe='Equipe '.$texte->getEquipe()->getInfoequipenat().' ('.$texte->getNom().')';
                    ;
                    $titre= $section->addText($titreEquipe);
                    $titre->setFontStyle('styletitre');

                    $textlines = explode("\n", $texte->getTexte());

                    $textrun = $section->addTextRun();
                    $textrun->addText(array_shift($textlines), 'styletexte');
                    foreach($textlines as $line) {
                        $textrun->addTextBreak();
                        // maybe twice if you want to seperate the text
                        // $textrun->addTextBreak(2);
                        $textrun->addText($line, 'styletexte');
                    }
                    //$lineStyle = array('weight' => 1, 'width' => 200, 'height' => 0, 'color'=> '0000FF');
                    $section->addTextBreak(3);
                    //$section->addLine($lineStyle);
                    $texte=$section->addText('------',null,'pstyle');
                }
            }
        }
        $categorie=$type;
        $filesystem = new Filesystem();
        $fileName = $this->session->get('edition')->getEd().'livre d\'or '.$categorie.'.docx';

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord,'Word2007');
        $objWriter ->save($this->getParameter('app.path.tempdirectory').'/'.$fileName);
        $response = new Response(file_get_contents($this->getParameter('app.path.tempdirectory').'/'.$fileName));//voir https://stackoverflow.com/questions/20268025/symfony2-create-and-download-zip-file
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $fileName
        );
        $response->headers->set('Content-Type','application/msword');
        $response->headers->set('Content-Disposition', $disposition);
        $filesystem->remove($this->getParameter('app.path.tempdirectory').'/'.$fileName);
        return $response;







    }
         
          
          
}

