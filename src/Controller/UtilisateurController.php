<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Equipesadmin;
use App\Entity\Elevesinter;
use App\Entity\Rne;
use App\Service\Mailer;
use App\Form\UserType;
use App\Form\UserRegistrationFormType;
use App\Form\InscrireEquipeType;
use App\Form\ModifEquipeType;
use App\Form\ResettingType;
use App\Form\ProfileType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Mailer\MailerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class UtilisateurController extends AbstractController
{    private $session;
   
    public function __construct(SessionInterface $session)
        {
            $this->session = $session;
        }
    
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        $rneRepository=$this->getDoctrine()->getManager()->getRepository('App:Rne');
        
        // création du formulaire
        $user = new User();
        // instancie le formulaire avec les contraintes par défaut, + la contrainte registration pour que la saisie du mot de passe soit obligatoire
        $form = $this->createForm(UserRegistrationFormType::class, $user,[
           'validation_groups' => array('User', 'registration'),
        ]);        
        $form->handleRequest($request);  
        if ($form->isSubmitted() && $form->isValid()) {
            
             $rne=$form->get('rne')->getData();
             if ($rneRepository->findOneByRne(['rne'=>$rne])==null){
             $request->getSession()
                                ->getFlashBag()
                                ->add('alert', 'Ce n° RNE n\'est pas valide !') ;   
          
             return   $this->redirectToRoute('register');
            }    
            // Encode le mot de passe
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            //inactive l'User en attente de la vérification du mail
            $user->setIsActive(0);
            $user->setToken($tokenGenerator->generateToken());
            // enregistrement de la date de création du token
            $user->setPasswordRequestedAt(new \Datetime());
          
           
            
            // Enregistre le membre en base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user); 
            $em->flush();

            $bodyMail = $mailer->createBodyMail('register/verif_mail.html.twig', [
                'user' => $user ]);
            $mailer->sendMessage('info@olymphys.fr', $user->getEmail(), 'Vérification de l\'adresse mail', $bodyMail);//'info@olymphys.fr'
            $request->getSession()->getFlashBag()->add('success', "Un mail va vous être envoyé afin que vous puissiez finaliser votre inscription. Le lien que vous recevrez sera valide 24h.");

            return $this->redirectToRoute("core_home");

        }
        return $this->render('register/register.html.twig',
            array('form' => $form->createView())
        );
    }
    


    // si supérieur à 24h, retourne false
    // sinon retourne false
    private function isRequestInTime(\Datetime $passwordRequestedAt = null)
    {
        if ($passwordRequestedAt === null)
        {
            return false;        
        }
        
        $now = new \DateTime();
        $interval = $now->getTimestamp() - $passwordRequestedAt->getTimestamp();

        $daySeconds = 60 * 60 * 24;
        $response = $interval > $daySeconds ? false : $reponse = true;
        return $response;
    }
    
    /**
     * @Route("/verif_mail/{id}/{token}", name="verif_mail")
     */
    public function verifMail(User $user, Request $request, Mailer $mailer, string $token)
    {
 
       // interdit l'accès à la page si:
        // le token associé au membre est null
        // le token enregistré en base et le token présent dans l'url ne sont pas égaux
        // le token date de plus de 24h
        if ($user->getToken() === null || $token !== $user->getToken() || !$this->isRequestInTime($user->getPasswordRequestedAt()))
        {
            throw new AccessDeniedHttpException();
        }

            // réinitialisation du token à null pour qu'il ne soit plus réutilisable
            $user->setToken(null);
            $user->setPasswordRequestedAt(null);
            $user->setIsActive(1);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $bodyMail = $mailer->createBodyMail('register/mail_nouvel_user.html.twig', ['user' => $user]);
            //$mailer->sendMessage('info@olymphys.fr', 'webmestre2@olymphys.fr', 'Inscription d\'un nouvel utilisateur', $bodyMail);
            $mailer->sendMessage('info@olymphys.fr','info@olymphys.fr', 'Inscription d\'un nouvel utilisateur', $bodyMail);
            $request->getSession()->getFlashBag()->add('success', "Votre inscription est terminée, vous pouvez vous connecter.");

            return $this->redirectToRoute('login');

        
    }
    
         /**
     * @Route("/forgottenPassword", name="forgotten_password")
     */
    public function forgottenPassword(Request $request, Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {

        // création d'un formulaire "à la volée", afin que l'internaute puisse renseigner son mail
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(),
                    new NotBlank()
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(User::class)->findOneByEmail($form->getData()['email']);

            // aucun email associé à ce compte.
            if (!$user) {
                $request->getSession()->getFlashBag()->add('warning', "Cet email ne correspond pas à un compte.");
                return $this->redirectToRoute("forgotten_password");
            } 

            // création du token
            $user->setToken($tokenGenerator->generateToken());
            // enregistrement de la date de création du token
            $user->setPasswordRequestedAt(new \Datetime());
            $em->flush();

            // on utilise le service Mailer créé précédemment
            $bodyMail = $mailer->createBodyMail('password/mail.html.twig', [
                'user' => $user
            ]);
            $mailer->sendMessage('info@olymphys.fr', $user->getEmail(), 'Renouvellement du mot de passe', $bodyMail);
            $request->getSession()->getFlashBag()->add('success', "Un mail va vous être envoyé afin que vous puissiez renouveler votre mot de passe. Le lien que vous recevrez sera valide 24h.");

            return $this->redirectToRoute("core_home");
        }

        return $this->render('password/request.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/reset_password/{id}/{token}", name="reset_password")
     */
    public function resetPassword(User $user, Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
 
       // interdit l'accès à la page si:
        // le token associé au membre est null
        // le token enregistré en base et le token présent dans l'url ne sont pas égaux
        // le token date de plus de 10 minutes
        if ($user->getToken() === null || $token !== $user->getToken() || !$this->isRequestInTime($user->getPasswordRequestedAt()))
        {
            throw new AccessDeniedHttpException();
        }

        $form = $this->createForm(ResettingType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // réinitialisation du token à null pour qu'il ne soit plus réutilisable
            $user->setToken(null);
            $user->setPasswordRequestedAt(null);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', "Votre mot de passe a été renouvelé.");

            return $this->redirectToRoute('login');

        }

        return $this->render('password/index.html.twig', [
            'form' => $form->createView()
        ]);
        
    }
       
    /**
     * @Route("/profile_show", name="profile_show")
     */
    public function profileShow()
    {
        $user = $this->getUser();
        return $this->render('profile/show.html.twig', array(
            'user' => $user,
        ));
    }
    
    /**
     * Edit the user.
     *
     * @param Request $request
     * @Route("profile_edit", name="profile_edit")
     */
    public function profileEdit(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('core_home');
        }
        return $this->render('profile/edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
    }
    
     /**
     * 
     *
     *  
     * @Route("/Utilisateur/inscrire_equipe,{idequipe}", name="inscrire_equipe")
     */
    public function inscrire_equipe (Request $request,Mailer $mailer,$idequipe)
    {   $date = new \datetime('now');
    
   
    
        if($date<$this->session->get('edition')->getDateouverturesite()){
        
           $request->getSession()
                                                     ->getFlashBag()
                                                     ->add('info', 'Les inscriptions sont closes, Inscriptions entre le '.$this->session->get('edition')->getDateouverturesite()->format('d-m-Y').' et le '.$this->session->get('edition')->getDatelimcia()->format('d-m-Y').' 22 heures(heure de Paris)') ;
            
            
            return $this->redirectToRoute('core_home');
        
    
        }
        $em=$this->getDoctrine()->getManager();
         $repositoryEquipesadmin=$em->getRepository('App:Equipesadmin');
         $repositoryEleves=$em->getRepository('App:Elevesinter');
        if( null!=$this->getUser()){
            
         if ($this->getUser()->getRoles()[0]=='ROLE_PROF'){
         $edition=$this->session->get('edition');
         $edition=$em->merge($edition);
         if ($idequipe=='x'){
         $equipe = new Equipesadmin(); 
          $form1=$this->createForm(InscrireEquipeType::class, $equipe,['rne'=>$this->getUser()->getRne()]);
         $modif=false;
         }
         else{
           $equipe=   $repositoryEquipesadmin->findOneById(['id'=>intval($idequipe)]);
           $eleves= $repositoryEleves->findByEquipe(['equipe'=>$equipe]);
           $form1=$this->createForm(ModifEquipeType::class, $equipe,['rne'=>$this->getUser()->getRne(),'eleves'=>$eleves]); 
           $modif=true;
         }
         
         $form1->handleRequest($request); 
          if ($form1->isSubmitted() && $form1->isValid()){
              
              $repositoryRne=$em->getRepository('App:Rne');
               
              $lastEquipe=$repositoryEquipesadmin->createQueryBuilder('e')
                                                                            ->select('e, MAX(e.numero) AS max_numero')
                                                                            ->andWhere('e.edition = :edition')
                                                                            ->setParameter('edition', $edition)
                                                                            ->getQuery()->getSingleResult();
              
              if(($lastEquipe['max_numero']==null) and ($modif==false)){
                  $numero=1;
                $equipe->setNumero($numero);
              }
              elseif( $modif==false){
                  $numero= intval($lastEquipe['max_numero'])+1;
                  $equipe->setNumero($numero);
              }
              
             $rne_objet=$repositoryRne->findOneByRne(['rne'=>$this->getUser()->getRne()]);
             $equipe->setPrenomprof1($form1->get('idProf1')->getData()->getPrenom());
             $equipe->setNomprof1($form1->get('idProf1')->getData()->getNom());
             $equipe->setPrenomprof2($form1->get('idProf2')->getData()->getPrenom());
             $equipe->setNomprof2($form1->get('idProf2')->getData()->getNom());
             $equipe->setEdition($edition);
             $equipe->setRne($this->getUser()->getRne());
             $equipe->setRneid($rne_objet);
             $equipe->setDenominationLycee($rne_objet->getDenominationPrincipale());
             $equipe->setNomLycee($rne_objet->getAppellationOfficielle());
             $equipe->setLyceeAcademie($rne_objet->getAcademie());
             $equipe->setLyceeLocalite($rne_objet->getAcheminement()); 
             
             $em->persist($equipe);
             $em->flush();
             
               for($i=1;$i<7;$i++){
                  if ($form1->get('prenomeleve'.$i)->getData()!=null){
                     try {
                         
                        $id= $form1->get('id'.$i)->getData();
                        $eleve[$i]=$repositoryEleves->find(['id'=>$form1->get('id'.$i)->getData()]);
                     } catch (\Exception $ex) {
                              $eleve[$i]=new Elevesinter(); 
                     }
                     
                      $eleve[$i]->setPrenom($form1->get('prenomeleve'.$i)->getData());
                      $eleve[$i]->setNom($form1->get('nomeleve'.$i)->getData());
                      $eleve[$i]->setCourriel($form1->get('maileleve'.$i)->getData());
                      $eleve[$i]->setGenre($form1->get('genreeleve'.$i)->getData());
                      $eleve[$i]->setClasse($form1->get('classeeleve'.$i)->getData());
                      $eleve[$i]->setEquipe($equipe);
                      $em->persist($eleve[$i]);
                      $em->flush();
                  }
               }
             
             
             $mailer->sendConfirmeInscriptionEquipe($equipe,$this->getUser());
               
               
               
               
              return $this->redirectToRoute('fichiers_choix_equipe', array('choix' =>'liste_prof'));
              
          
          }
         return $this->render('register/inscrire_equipe.html.twig',array('form'=>$form1->createView(),'equipe'=>$equipe,'concours'=>$this->session->get('concours'),'choix'=>'liste_prof', 'modif'=>$modif));
             
         }
         else{  return $this->redirectToRoute('core_home');}
        }
        
        else{
      
            return $this->redirectToRoute('login');
           
        }
        
        
        
    }
     /**
     * 
     *
     *  
     * @Route("/Utilisateur/desinscrire_equipe,{idequipe}", name="desinscrire_equipe")
     */
    public function desinscrire_equipe (Request $request,Mailer $mailer,$idequipe){
         $em=$this->getDoctrine()->getManager();
         $repositoryEquipesadmin=$em->getRepository('App:Equipesadmin');
         
         $equipe= $repositoryEquipesadmin->find(['id'=>$idequipe]);
         $equipe->setInscrite(false);
         $em->persist($equipe);
         $em->flush;
        
        
    }
    
    
    
}