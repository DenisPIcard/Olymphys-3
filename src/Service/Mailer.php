<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Equipesadmin;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Twig\Environment;

class Mailer
{
    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
    
        public function sendWelcomeMessage(User $user)
    {
        $email = (new TemplatedEmail())
            ->from(new Address('alienmailcarrier@example.com', 'The Space Bar'))
            ->to(new Address($user->getEmail(), $user->getNom()))
            ->subject('Welcome to the Space Bar!')
            ->htmlTemplate('email/welcome.html.twig')
            ->context([
              'user' => $user,
            ]);
        $this->mailer->send($email);
        return $email;
    }
    public function sendConfirmFile(Equipesadmin $equipe, $type_fichier ){
     $email=(new Email())
                    ->from('alain.jouve@wanadoo.fr')
                    ->to('webmestre3@olymphys.fr') //'webmestre2@olymphys.fr', 'Denis'
                    ->subject('Depot du '.$type_fichier.'de l\'équipe '.$equipe->getInfoequipe())
                    ->text('L\'equipe '. $equipe->getInfoequipe().' a déposé un fichier : '.$type_fichier);
                   
       $this->mailer->send($email);
        return $email;
    
    }
     public function sendConfirmeInscriptionEquipe(Equipesadmin $equipe ){
     $email=(new Email())
                    ->from('info@olymphys.fr')
                    ->to('9452279e33-11d237@inbox.mailtrap.io') //'webmestre2@olymphys.fr', 'Denis'
                    ->subject('Inscription de l\'équipe n° '.$equipe->getNumero().' par '.$equipe->getIdProf1()->getPrenomNom())
                    ->html('Bonjour<br>
                            Nous confirmons que '.$equipe->getIdProf1()->getPrenomNom().'(<a href="'.$equipe->getIdProf1()->getEmail().'">'.$equipe->getIdProf1()->getEmail().
                            '</a>) a inscrit une nouvelle équipe denommée : '.$equipe->getTitreProjet().
                            '<br> <br>Le comité national des Olympiades de Physique');
                   
       $this->mailer->send($email);
        return $email;
    
    }
    
    
}

