<?php
namespace App\Controller;


use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Form\ProfileType;
use App\Form\ResettingType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpClient\HttpClient;
use Guzzle\Http\Client;
class OauthController extends AbstractController
{  
             
     /**
 * @Route("/oauth/authorize", name="oauth_authorize");
 * 
 */
   public function authorize(Request $request, AuthenticationUtils $authenticationUtils)
   {    
       $client = HttpClient::create();
       //dd($request);
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
       // dd($lastUsername);
        //return $this->render($request->query->get('redirect_uri'));
       
       $response= $client->request('GET',$request->query->get('redirect_uri').'/olymphys');
       
     
   }
    /**
 * @Route("/oauth/access_token", name="oauth_access_token");
 * 
 */
   public function access_token(Request $request)
   {    
       
   }
   
}

