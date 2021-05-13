<?php
// src/Controller/CoreController.php
namespace App\Controller;

use datetime;
use Exception as Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class CoreController extends AbstractController
{
    private SessionInterface $session;
   
    public function __construct(SessionInterface $session)
        {
            $this->session = $session;
        }

    /**
     * @Route("/", name="core_home")
     * @param TokenGeneratorInterface $tokenGenerator
     * @return RedirectResponse|Response
     * @throws Exception
     */
  public function index(TokenGeneratorInterface $tokenGenerator)
  {
      $user = $this->getUser();
      $repositoryEdition = $this->getDoctrine()->getRepository('App:Edition');
      $edition = $repositoryEdition->findOneBy([],['id' => 'desc']);
      $this->session->set('edition', $edition);
      //dd($this->session);
      if (null != $user) {
          //Pour obliger l'utilisateur dont le compte a été créé par les admin à mettre à jour son mot de passe
          if($user->getLastVisit()==NULL) {
              $em = $this->getDoctrine()->getManager();
              $user->setToken($tokenGenerator->generateToken());
              // enregistrement de la date de création du token
              $user->setPasswordRequestedAt(new Datetime());
              $em->persist($user);
              $em->flush();
           return $this->redirectToRoute('reset_password', [ 'id'=> $user->getId(),'token' => $user->getToken()]);
          }

          $datecia = $edition->getConcourscia();
          $dateconnect = new datetime('now');
          if ($dateconnect > $datecia) {
              $concours = 'national';
          }
          else                                                                                                                                     {
              $concours = 'interacadémique';
          }

          $datelimphotoscia = date_create();
          $datelimphotoscn = date_create();
          $datelimdiaporama = new DateTime($this->session->get('edition')->getConcourscn()->format('Y-m-d'));
          $datelimlivredor = new DateTime($this->session->get('edition')->getConcourscn()->format('Y-m-d'));
          $datelivredor = new DateTime($this->session->get('edition')->getConcourscn()->format('Y-m-d') . '00:00:00');
          $datelimlivredoreleve = new DateTime($this->session->get('edition')->getConcourscn()->format('Y-m-d') . '18:00:00');
          date_date_set($datelimphotoscia, $edition->getconcourscia()->format('Y'), $edition->getconcourscia()->format('m'), $edition->getconcourscia()->format('d') + 17);
          date_date_set($datelimphotoscn, $edition->getconcourscn()->format('Y'), $edition->getconcourscn()->format('m'), $edition->getconcourscn()->format('d') + 30);
          date_date_set($datelivredor, $edition->getconcourscn()->format('Y'), $edition->getconcourscn()->format('m'), $edition->getconcourscn()->format('d') - 1);
          date_date_set($datelimdiaporama, $edition->getconcourscn()->format('Y'), $edition->getconcourscn()->format('m'), $edition->getconcourscn()->format('d') - 7);
          date_date_set($datelimlivredor, $edition->getconcourscn()->format('Y'), $edition->getconcourscn()->format('m'), $edition->getconcourscn()->format('d') + 8);
          $this->session->set('concours', $concours);
          $this->session->set('datelimphotoscia', $datelimphotoscia);
          $this->session->set('datelimphotoscn', $datelimphotoscn);
          $this->session->set('datelivredor', $datelivredor);
          $this->session->set('datelimlivredor', $datelimlivredor);
          $this->session->set('datelimlivredoreleve', $datelimlivredoreleve);
          $this->session->set('datelimdiaporama', $datelimdiaporama);
          $this->session->set('dateclotureinscription', new DateTime($this->session->get('edition')->getConcourscn()->format('Y-m-d H:i:s')));
      }


      return $this->render('core/index.html.twig');
  }
    

}
