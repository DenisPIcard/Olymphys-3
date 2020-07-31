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
use App\Entity\Centrescia;



class FichiersequipesFilterType extends FilterType
{ use FilterTypeTrait;
    
    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    { 
       
        $datas =$form->getParent()->getData();
    
      if(isset($datas['edition'])){
            
         $queryBuilder->andWhere( 'entity.edition =:edition')
                              ->setParameter('edition',$datas['edition']);
       }     
       if(isset($datas['centre'])){
                  
           $queryBuilder->andWhere( 'eq.centre=:centre')
                              ->setParameter('centre',$datas['centre']);
             
                  } 
      
       
       return $queryBuilder;
         
    }
    
     public function configureOptions(OptionsResolver $resolver)
    {    $resolver->setDefaults([
            'choice_label' => [
                'Edition' => 'edition',
                'Centre' => 'centre',
                // ...
            ],
        ]);
       
    }

    public function getParent()
    {
        return EntityType::class;
    }

   
}



