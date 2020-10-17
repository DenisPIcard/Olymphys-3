<?php
// src/Controller/CoreController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\User;
use App\Entity\Edition;  
class CoreController extends AbstractController
{    
    private $session;
   
    public function __construct(SessionInterface $session)
        {
            $this->session = $session;
        }
    
    
    /**
     * @Route("/", name="core_home")
     */
  public function index(SessionInterface $session)
  {  
     
     $user=$this->getUser();
   // dump($user);
   $repositoryEdition = $this->getDoctrine()->getRepository('App:Edition');
                  $edition=$repositoryEdition->findOneBy([], ['id' => 'desc']);
                  
     $this->session->set('edition', $edition); 
    if (null != $user)
    {    
     $datelimcia = $edition->getDatelimcia();
    $datelimnat=$edition->getDatelimnat(); 
     $dateouverturesite=$edition->getDateouverturesite();
    $dateconnect= new \datetime('now');
      if ($dateconnect>$datelimcia) {
        $concours='national';
   }
    if (($dateconnect>$dateouverturesite) and ($dateconnect<=$datelimcia)) {
        $concours= 'interacadémique';
    }
     
     $this->session->set('concours', $concours);          
     //dd($this->session);
     //$session->set('edition',$edition);  
     //  dd($user);
     //$session->set('user', $user);
     
    }
    
    return $this->render('core/index.html.twig');
  }
    

}
