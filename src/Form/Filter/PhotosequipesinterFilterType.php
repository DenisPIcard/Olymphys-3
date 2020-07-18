<?php

namespace App\Form\Filter;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterTypeTrait;
//use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType ; 
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Edition ;
use App\Entity\Equipesadmin;
use App\Entity\Elevesinter;



class PhotosequipesinterFilterType extends FilterType
{ 
    
    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    { 
       
        
       $datas =$form->getParent()->getData();
    
      if(method_exists($datas['edition'], 'getId')){
            
         $queryBuilder->andWhere( 'entity.edition =:edition')
                                 ->andWhere('entity.national =:national')
                                 ->setParameter('national', 'FALSE')  
                              ->setParameter('edition',$datas['edition']);
       }     
       if(method_exists($datas['centre'],'getId')){
                  
           $queryBuilder->andWhere( 'eq.centre=:centre')
                              ->setParameter('centre',$datas['centre'])
                             ->andWhere('entity.national =:national')
                              ->setParameter('national', 'FALSE')  ;
             
                  } 
                 
                  //dd($datas['equipe']);
       if(method_exists($datas['equipe'],'getId')){
                    
           $queryBuilder->andWhere( 'entity.equipe =:equipe')
                               ->setParameter('equipe',$datas['equipe'])
                               ->andWhere( 'entity.national = FALSE')
                               ->addOrderBy('eq.numero','ASC');
                                          
       }
       
      
       return $queryBuilder;
         
    }
    
     public function configureOptions(OptionsResolver $resolver)
    {    $resolver->setDefaults([
            'choice_label' => [
                'Edition' => 'edition',
                'Equipe'=> 'equipe'
                // ...
            ],
        ]);
       
    }

    public function getParent()
    {
        return EntityType::class;
    }

   
}



