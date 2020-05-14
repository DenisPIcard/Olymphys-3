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

class OauthController extends AbstractController
{  
     /**
 * @Route("/oauth/authorize", name="oauth_authorize");
 * 
 */
   public function oauth(Request $request)
   {    $client = HttpClient::create();
    
       
      
       
       $accessToken = 'abcd1234def67890';

$request = $client->request('POST', 'http://192.168.1.37/phpbb3')->addHeader('Authorization', 'Bearer '.$accessToken);;


$response = $request->send();
   }
}

