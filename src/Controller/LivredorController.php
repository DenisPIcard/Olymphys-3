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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class LivredorController extends AbstractController
{     private $session;
   
    public function __construct(SessionInterface $session)
        {
            $this->session = $session;
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
       $content = $this
                 ->renderView('livredor\saisie_texte.html.twig', ['id'=>'equipe-'.$equipes[0]->getId()]);
        
       return new Response($content); 
       
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
                                 ->leftJoin('l.prof', 'u')
                                 ->orderBy('l.edition', 'DESC')
                                 ->addOrderBy('u.nom','ASC')
                                 ->getQuery()->getResult();
          $i=0;
            foreach($listetextes as $texte){
               $idprof=$texte->getProf()->getId();
               $equipes[$i]= $qb=$this->getDoctrine()
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
                               ->addOrderBy('e.lettre', 'ASC')
                               ->getQuery()->getResult();
               
           }
                    $content = $this
                                    ->renderView('livredor\lire.html.twig', ['listetextes'=>$listetextes, 'equipes' =>$equipes,'choix'=>$type]);;
                    }
        return new Response($content);
    
    }
 
}