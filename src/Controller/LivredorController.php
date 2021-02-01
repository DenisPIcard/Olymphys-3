<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller ;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request ;
use App\Entity\Equipesadmin;
use App\Entity\Edition;
use App\Entity\Elevesinter;
use App\Entity\Livredoreleves;
use App\Entity\Livredorprofs;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\HeaderUtils;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Alignment;
use Symfony\Component\Filesystem\Filesystem;
class LivredorController extends AbstractController
{     private $edition;
   
    public function __construct(SessionInterface $session)
        {
            $this->session = $session;
            $this->session->get('edition');
        }
    
    
    /**
     *  @IsGranted("ROLE_PROF")
     * @Route("/livredor/choix_equipe", name="livredor_choix_equipe")
     *  @return RedirectResponse|Response
     */
    public function choix_equipe(Request $request){
        
        $idprof=$this->getUser()->getId();
          $qb=$this->getDoctrine()
                                ->getManager()
                                ->getRepository('App:Equipesadmin')
                               ->createQueryBuilder('e')
                               ->where('e.edition =:edition')
                               ->setParameter('edition', $this->session->get('edition'))
                               ->andWhere('e.idProf1 =:prof1')
                               ->setParameter('prof1',$idprof)
                               ->orWhere('e.idProf2 =:prof2')
                               ->setParameter('prof2',$idprof)
                               ->andWhere('e.selectionnee = 1')
                               ->addOrderBy('e.lettre', 'ASC');;
           $equipes = $qb->getQuery()->getResult();
         if (count($equipes)>1){
        $form = $this->createFormBuilder()
                    ->add('equipe', EntityType::class,
                              ['class'=>Equipesadmin::class,
                                  'query_builder' => $qb,
                                'choice_label'=>'getInfoequipenat',        
                    ])
            ->add('save', SubmitType::class, ['label' => 'Valider'])
            ->getForm();
          $form->handleRequest($request); 
    if ($form->isSubmitted() && $form->isValid()){
        
        $equipe=$form->get('equipe')->getData();
         $id=$equipe->getId();
      return  $this->redirectToRoute('livredor_saisie_texte',['id'=>'equipe-'.$id]) ;
            }
             $content = $this
                 ->renderView('livredor\choix_equipe.html.twig', ['form'=>$form->createView()]);
        
       return new Response($content); 
         }
   else{
       return  $this->redirectToRoute('livredor_saisie_texte',['id'=>'equipe-'.$equipes[0]->getId()]) ;
       
   }     
        
    }
   /**
     * @IsGranted("ROLE_PROF")
     * @Route("/livredor/saisie_texte,{id}", name="livredor_saisie_texte")
     *  @return RedirectResponse|Response
     */
    public function saisie_texte(Request $request, $id) : Response
    {   
        $em=$this->getDoctrine()->getManager();
        $edition=$this->session->get('edition');
         $edition=$em->merge($edition);
         
         $form = $this->createFormBuilder();
       
         $ids=explode('-',$id);
        $type=$ids[0];
        $id_=$ids[1];
       
        if($type=='equipe'){
        
        $equipe=$this->getDoctrine()
                                ->getManager()
                                ->getRepository('App:Equipesadmin')->findOneById(['id'=>$id_]);   
        
        $livredor=$this->getDoctrine()
                                ->getManager()
                                ->getRepository('App:Livredoreleves')->findOneByEquipe(['equipe'=>$equipe]);
      if($livredor != null){   
          $texteini=$livredor->getTexte();
      }
              if (!isset($texteini)) { 
             $texteini ='';
                            };
         
        $listeEleves=$this->getDoctrine()
                                ->getManager()
                                ->getRepository('App:Elevesinter')
                                ->createQueryBuilder('e')
                               ->where('e.equipe =:equipe')
                               ->setParameter('equipe', $equipe)
                               ->getQuery()->getResult();
        $noms='';
         foreach($listeEleves as $eleve){
             $noms= $noms.$eleve->getPrenom().', ';
             
         }
         $noms= substr($noms, 0, -2);
         
        }
        if($type=='prof'){
            
            $prof=$this->getDoctrine()
                                 ->getManager()
                                 ->getRepository('App:User')->findOneById(['id'=>$id_]); 
            $livredor=$this->getDoctrine()
                                ->getManager()
                                ->getRepository('App:Livredorprofs')->findOneByProf(['prof'=>$prof]);
      if($livredor != null){   
          $texteini=$livredor->getTexte();
         
      }
              if (!isset($texteini)) { 
             $texteini ='';
                            };
            
        }
       $form->add('texte', TextareaType::class,[
                'label' =>'Texte (1000 char. maxi)',
                'data' => $texteini,
            ])    
            ->add('save', SubmitType::class, ['label' => 'Valider']);
           $form=$form ->getForm();
        
          $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()){
         $texte=$form->get('texte')->getData();
         if(($type == 'equipe')){
       
         
        $livredor=$this->getDoctrine()
                                 ->getManager()
                                 ->getRepository('App:Livredoreleves')->findOneByEquipe(['equipe'=>$equipe]);     
       if ($livredor==null){
            $livredor=new livredoreleves();
           } 
            
             $livredor->setNoms($noms);
            $livredor->setTexte($texte);
            $livredor->setEquipe($equipe);
            $livredor->setEdition($edition);
           }
         if($type == 'prof'){ 
         try {       
        $livredor=$this->getDoctrine()->getManager()->getRepository('App:Livredorprofs')
                                 ->createQueryBuilder('p')
                                 ->Where('p.edition =:edition')
                                 ->setParameter('edition',$edition)
                                 ->andWhere('p.prof =:prof')
                                -> setParameter('prof',$prof)
         ->getQuery()->getSingleResult();
                 }
         catch (\Exception $e){
             $livredor=null;
         }
         if ($livredor==null){
            $livredor=new livredorprofs();
           }
            $livredor->setNom($prof->getPrenom().' '.$prof->getNom());
            $livredor->setProf($prof);
            $livredor->setTexte($texte);
            $livredor->setEdition($edition);
      }    
           
             $em->persist($livredor);
             $em->flush();
            return  $this->redirectToRoute('core_home') ;
            
   
   
    }
  
     if ($type=='equipe'){
        $content = $this
                          ->renderView('livredor\saisie_texte.html.twig', ['form'=>$form->createView(),'equipe' =>$equipe,'type'=>'equipe']);}
       if ($type=='prof'){
        $content = $this
                                ->renderView('livredor\saisie_texte.html.twig', ['form'=>$form->createView(),'prof' =>$this->getUser(),'type'=>'prof']);}
       return new Response($content);
        
    } 
    /**
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @Route("/livredor/lire,{type}", name="livredor_lire")
     *  @return RedirectResponse|Response
     */
    public function lire(Request $request,$type) : Response
    {   
        
        $em=$this->getDoctrine()->getManager();
        $edition= $this->session->get('edition');
         $edition=$em->merge($edition);
      
        if ($type=='eleves'){
            $listetextes=$this->getDoctrine()
                                 ->getManager()
                                 ->getRepository('App:Livredoreleves')->CreateQueryBuilder('l')
                                 ->leftJoin('l.equipe', 'eq')
                                 ->orderBy('l.edition', 'DESC')
                                 ->addOrderBy('eq.lettre','ASC')
                                 ->getQuery()->getResult();
                    $content = $this
                 ->renderView('livredor\lire.html.twig', ['listetextes'=>$listetextes, 'choix'=>$type]);;
        }
        if ($type=='profs'){
            $listetextes=$this->getDoctrine()
                                 ->getManager()
                                 ->getRepository('App:Livredorprofs')->CreateQueryBuilder('l')
                                 ->select('l')
                                 ->andWhere('l.edition =:edition')
                                  ->setParameter('edition', $edition)
                                 ->leftJoin('l.prof', 'u')
                                 ->addOrderBy('u.nom','ASC')
                                 ->getQuery()->getResult();
           $equipes=$this->getDoctrine()
                                ->getManager()
                                ->getRepository('App:Equipesadmin')
                               ->createQueryBuilder('e')
                               ->Where('e.edition =:edition')
                               ->setParameter('edition', $edition)
                               ->andWhere('e.selectionnee = 1')
                               ->addOrderBy('e.lettre', 'ASC')
                                ->getQuery()
                                ->getResult();
            $i=0;
            foreach($listetextes as $texte){
                             $prof = $texte->getProf();
                             $lettres_equipes_prof[$i] ='';
                             foreach($equipes as $equipe){ 
                                 
                               
                                 if (($equipe->getIdProf1() == $prof->getId()) or ($equipe->getIdProf2() == $prof ->getId()) ){
                                      if (strlen($lettres_equipes_prof[$i])>0){
                                     $lettres_equipes_prof[$i]= $lettres_equipes_prof[$i].', '.$equipe->getLettre();
                                      }
                                      if (strlen($lettres_equipes_prof[$i])==0) { $lettres_equipes_prof[$i]=$lettres_equipes_prof[$i].$equipe->getLettre();}
                                  
                                 }
                               
                             }
               
              
               
            /*   $qb=$this->getDoctrine()
                                ->getManager()
                                ->getRepository('App:Equipesadmin')
                               ->createQueryBuilder('e')
                               ->Where('e.edition =:edition')
                               ->setParameter('edition', $edition)
                               ->andWhere('e.idProf1 =:prof1')
                               ->setParameter('prof1',$idprof)
                               ->orWhere('e.idProf2 =:prof2')
                               ->setParameter('prof2',$idprof)
                                ->andWhere('e.selectionnee = 1')
                               ->addOrderBy('e.lettre', 'ASC');
               
            $equipes[$i]=$qb->getQuery()->getResult();*/
               $i=$i+1;
           } 
            
           //dd($equipes);
         
                    $content = $this
                                    ->renderView('livredor\lire.html.twig', ['listetextes'=>$listetextes, 'lettres_equipes_prof' =>$lettres_equipes_prof,'choix'=>$type]);;
                    }
        return new Response($content);
    
    }
    /**
     * @IsGranted("ROLE_COMITE")
     * @Route("/livredor/choix_editer", name="livredor_choix_editer")
     * 
     */
    public function choix_editer(Request $request) {
        
        
         $form = $this->createFormBuilder();
        
       $form ->add('eleves', SubmitType::class, ['label' => 'Livre d\'or élèves'])
                 ->add('profs', SubmitType::class, ['label' => 'Livre d\'or profs']);
           $form=$form ->getForm();
        
          $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()){
        
        $phpWord = new  PhpWord();
        $section = $phpWord->addSection();
        $paragraphStyleName = 'pStyle';
        $phpWord->addParagraphStyle($paragraphStyleName, array( 'alignment'  => 'center', 'spaceAfter' => 100));

        $phpWord->addTitleStyle(1, array('bold' => true,  'size'=> 14 ,'spaceAfter' =>240, 'align'=>'center'));
        $fontTitre = 'styletitre';
                $phpWord->addFontStyle(
                    $fontTitre,
                    array('name' => 'Tahoma', 'size' => 12 , 'color' => '0000FF', 'bold' => true, 'align'=>'center')
                );
         //$fontTitre = new \PhpOffice\PhpWord\Style\Font();
          $fontTexte = 'styletexte';
                $phpWord->addFontStyle(
                    $fontTexte,
                    array('name' => 'AbrazoScriptSSK', 'size' => 16, 'color' => '000000')
                );
          
        if ($form->get('profs')->isClicked()){
            $livredor=$this->getDoctrine()
                                ->getManager()
                                ->getRepository('App:Livredorprofs')->createQueryBuilder('l')
                                                                                         ->leftJoin('l.prof','p')
                                                                                         ->addOrderBy('p.nom','ASC')
                                                                                        ->getQuery()->getResult();
            
           $equiperepository= $this->getDoctrine()
                                ->getManager()
                                ->getRepository('App:Equipesadmin');
             $section->addTexte('Livre d\'or des professeurs - Edition '.$this->session->get('edition')->getEd(), null, 'pStyle');
             if ($livredor!=null){
              foreach($livredor as $texte){ 
                  $prof=$texte->getProf();
                  
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
           
            $section->addText($texte->getTexte(),'styletexte');  
            //$lineStyle = array('weight' => 1, 'width' => 200, 'height' => 0, 'color'=> '0000FF');
            
            $section->addTextBreak(3);
             //$section->addLine($lineStyle);
           $section->addText('o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o');
              }
            
            $filesystem = new Filesystem();
            $fileName = $this->session->get('edition')->getEd().'livre d\'or profs.docx';  
         
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
               else{
             
                 return $this->redirectToRoute('core_home');
                }
          }
         if ($form->get('eleves')->isClicked()){
            $livredor=$this->getDoctrine()
                                ->getManager()
                                ->getRepository('App:Livredoreleves')
                                ->createQueryBuilder('e')
                                ->where('e.edition =:edition')
                                ->setParameter('edition',$this->session->get('edition'))
                                ->leftJoin('e.equipe','eq')
                                ->orderBy('eq.lettre','ASC')
                               ->getQuery()->getResult();
                                
          if ($livredor!=null)
             $section->addTexte('Livre d\'or des élèves- Edition '.$this->session->get('edition')->getEd(), null, 'pStyle');
         foreach($livredor as $texte){ 
          
           $equipe= $texte->getEquipe();
           
            $titreEquipe='Equipe '.$texte->getEquipe()->getInfoequipenat().' ('.$texte->getNoms().')';
           ;
           $titre= $section->addText($titreEquipe);  
           $titre->setFontStyle('styletitre');
           
            $texte=$section->addText($texte->getTexte());  
            $texte->setFontStyle('styletexte');
            //$lineStyle = array('weight' => 1, 'width' => 200, 'height' => 0, 'color'=> '0000FF');
            $section->addTextBreak(3);
            //$section->addLine($lineStyle);   
            $texte=$section->addText('o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o-o');
             }
             
         $filesystem = new Filesystem();
         $fileName=$this->session->get('edition')->getEd().'Livre-d-or-eleves.docx';
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
         else{
             
             return $this->redirectToRoute('core_home');
         }
       
       
   
    }
     $content = $this
                                    ->renderView('livredor\choix_editer.html.twig', ['form'=>$form->createView()]);
                    
        return new Response($content);   
        
    }
    
    
 
}