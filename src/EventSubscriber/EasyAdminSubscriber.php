<?php
# src/EventSubscriber/EasyAdminSubscriber.php
namespace App\EventSubscriber;

use App\Entity\Operations;
use App\Entity\Comptes;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
   

    public static function getSubscribedEvents()
    {
        return [
            'easy_admin.pre_persist' => ['setComptesSolde'],
            'easy_admin.pre_update'=>['UploadComptesSolde'],
           
            
        ];
    }

    public function setComptesSolde(GenericEvent $event)
    {
        $entity = $event->getSubject();
    
        if (!($entity instanceof Operations)) {
            return;
        }
        $compte=$entity->getComptes();
        $compte->setSolde($compte->getSolde()+$entity->getMontant());//le montant est négatif si débit d'ou le signe +
        $event['entity'] = $entity;
    }
    
    public function UploadComptesSolde(GenericEvent $event)
    {    
        $entity = $event->getSubject();
       
        if (!($entity instanceof Operations)) {
            return;
        }
        $compte=$entity->getComptes();
        $compte->setSolde($compte->getSolde()-$entity->getMontantini()+$entity->getMontant());//le montant est négatif si débit d'ou le signe +
        $event['entity'] = $entity;
    }
    
    
    
     public function Setmontantini(GenericEvent $event)
    {    
        $entity = $event->getSubject();
 dd($event);  
        if (!($entity instanceof Operations)) {
         
            return;
        }
         $entity->setMontantini($entity->getMontant());
         dd($entity);
        $event['entity'] = $entity;
    }
}


