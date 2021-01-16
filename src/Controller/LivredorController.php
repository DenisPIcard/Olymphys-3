<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller ;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
     * @Route("/livredor/choix_equipe", name="livredor_choix_equipe")
     *  @return RedirectResponse|Response
     */
    public function choix_equipe(Request $request){
        
                                         
         
        $form = $this->createFormBuilder()
                    ->add('equipe', EntityType::class,
                              ['class'=>Equipesadmin::class,
                                  'query_builder' => function (EntityRepository $er ) {
                        $edition=$this->session->get('edition');
                       
                                         return $er->createQueryBuilder('e') 
                                        ->where('e.edition =:edition')
                                       ->setParameter('edition',$edition)
                                       ->andWhere('e.selectionnee = 1')
                                       ->addOrderBy('e.lettre', 'ASC');  },
                                'choice_label'=>'getLettre',        
                    ])
            ->add('save', SubmitType::class, ['label' => 'Valider'])
            ->getForm();
          $form->handleRequest($request); 
    if ($form->isSubmitted() && $form->isValid()){
        
        $equipe=$form->get('equipe')->getData();
         $id=$equipe->getId();
      return  $this->redirectToRoute('livredor_saisie_texte',['id'=>$id]) ;
        
        
        
        
        
    }
             $content = $this
                 ->renderView('livredor\choix_equipe.html.twig', ['form'=>$form->createView()]);
        
       return new Response($content);  
        
        
    }
   /**
     * @Route("/livredor/saisie_texte,{id}", name="livredor_saisie_texte")
     *  @return RedirectResponse|Response
     */
    public function saisie_texte(Request $request, $id) : Response
    {   
        $em=$this->getDoctrine()->getManager();
        $edition=$this->session->get('edition');
         $edition=$em->merge($edition);
         
         $form = $this->createFormBuilder();
       
        $datelim =new \DateTime( $this->session->get('datelimlivredor')->format('Y-m-d').'00:00:00');
        
        $p=new \DateInterval('PT18H');
        $datelim=$datelim->add($p);
        
        
        
        if(($this->getUser()->getRoles()[0]=='ROLE_ELEVE') and(new \DateTime('now')<=$datelim)){
                     
            
        $this->session->set('equipe',$id);
        $equipe=$this->getDoctrine()
                                ->getManager()
                                ->getRepository('App:Equipesadmin')->findOneById(['id'=>$id]);   
         $form ->add('eleve', EntityType::class,
                              ['class'=>Elevesinter::class,
                                  'query_builder' => function (EntityRepository $er ) {
                                    $idequipe=$this->session->get('equipe');
                                         return $er->createQueryBuilder('e') 
                                            ->leftJoin('e.equipe','eq')
                                            ->Where('eq.id =:id')     
                                            ->setParameter('id', $idequipe)
                                            ->addOrderBy('e.nom', 'ASC');  
                                  },
                                 'choice_label'=>'getNomPrenomlivre',  
                    ]);
        
        }
        if($this->getUser()->getRoles()[0]=='ROLE_PROF'){
            
            $prof=$this->getDoctrine()
                                 ->getManager()
                                 ->getRepository('App:User')->findOneById(['id'=>$id]); 
        }
       $form->add('texte', TextareaType::class,[
                'label' =>'Texte (255 char. maxi)'
            ])    
            ->add('save', SubmitType::class, ['label' => 'Valider']);
           $form=$form ->getForm();
        
          $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()){
         $texte=$form->get('texte')->getData();
         if($this->getUser()->getRoles()[0]=='ROLE_ELEVE'){
        $eleve=$form->get('eleve')->getData();
         
        $livredor=$this->getDoctrine()
                                 ->getManager()
                                 ->getRepository('App:Livredoreleves')->findOneByEleve(['eleve'=>$eleve]);     
       if ($livredor==null){
            $livredor=new livredoreleves();
           } 
           $livredor->setNom($eleve->getPrenom());
            $livredor->setEleve($eleve);
            $livredor->setTexte($texte);
            $livredor->setEquipe($equipe);
            $livredor->setEdition($edition);
           }
         if($this->getUser()->getRoles()[0]=='ROLE_PROF'){
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
        $content = $this
                 ->renderView('livredor\saisie_texte.html.twig', ['form'=>$form->createView(),]);
        
       return new Response($content);
        
    } 
    /**
     * @Route("/livredor/lire,{type}", name="livredor_lire")
     *  @return RedirectResponse|Response
     */
    public function lire(Request $request,$type) : Response
    {   
        if ($type=='eleves'){
            $listetextes=$this->getDoctrine()
                                 ->getManager()
                                 ->getRepository('App:livredoreleves')->CreateQueryBuilder('l')
                                 ->leftJoin('l.equipe', 'eq')
                                 ->orderBy('l.edition', 'DESC')
                                 ->addOrderBy('eq.lettre','ASC')
                                 ->getQuery()->getResult()
                    ;
        }
        if ($type=='profs'){
            $listetextes=$this->getDoctrine()
                                 ->getManager()
                                 ->getRepository('App:livredorprofs')->CreateQueryBuilder('l')
                                 ->leftJoin('l.user', 'u')
                                 ->orderBy('l.edition', 'DESC')
                                 ->addOrderBy('u.nom','ASC')
                                 ->getQuery()->getResult();
        }
        
          $content = $this
                 ->renderView('livredor\lire.html.twig', ['listetextes'=>$listetextes]);
        
       return new Response($content);
        
        
        
    }

}