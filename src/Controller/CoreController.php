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
    /**
     * @Route("/", name="core_home")
     */
  public function index(SessionInterface $session)
  {  
     $user=$this->getUser();
   // dump($user);
   if (null != $user)
    { $repositoryEdition = $this->getDoctrine()->getRepository('App:Edition');
                  $edition=$repositoryEdition->findOneBy([], ['id' => 'desc']);
     $session->set('edition',$edition);  
     //  dd($user);
     $session->set('user', $user);
     
    }
    
    return $this->render('core/index.html.twig');
  }
    

}
