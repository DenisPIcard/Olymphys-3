<?php

namespace App\Form;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType ; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use App\Entity\Equipesadmin;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\TypeEntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class InscrireEquipeType extends AbstractType
{  
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
       
      $rne= $options['rne']   ;          
      
        $builder ->add('titreProjet', TextType::class, [
                                'label' => 'Titre du projet',
                                'mapped'=>true
              ])
                
                
                
          ->add('idProf1', EntityType::class, [
                            'class'=>'App:User',
                            'query_builder'=>function (EntityRepository $er) use($rne) {
                                                        return $er->createQueryBuilder('u')
                                                                ->andWhere('u.rne =:rne')
                                                                ->setParameter('rne',$rne)
                                                                ->addOrderBy('u.nom', 'ASC');
                                                                
                                                        ;},
                            'choice_value' => 'getId'                 ,                 
                            'choice_label' =>'getPrenomNom',
                             'mapped'=>false
                             ] )
          ->add('idProf2', EntityType::class, [
                            'class'=>'App:User',
                            'query_builder'=>function (EntityRepository $er) use($rne) {
                                                        return $er->createQueryBuilder('u')
                                                                ->andWhere('u.rne =:rne')
                                                                ->setParameter('rne',$rne)
                                                                ->addOrderBy('u.nom', 'ASC');
                            } ,
                           'choice_value' =>'getId',
                            'choice_label' =>'getPrenomNom',
                             'mapped'=>false
                             ] );
        for($i=1; $i<7;$i++) {        
          if($i<=1){   
         $builder->add('prenomeleve'.$i, TextType::class,[
                              'mapped' => false,
                             ]) 
                        ->add('nomeleve'.$i, TextType::class,[
                                             'mapped' => false,
                                            ])

                        ->add('maileleve'.$i, EmailType::class,[
                                             'mapped' =>false,
                                            ])
                        ->add('genreeleve'.$i, ChoiceType::class,[
                                             'mapped' =>false,
                                           'choices'=>['F'=>'F',
                                                              'M'=>'M']]);    
                   }
        if($i>1){$builder->add('prenomeleve'.$i, TextType::class,[
                              'mapped' =>false,
                              'required'=>false,
                             ]) 
                                  ->add('nomeleve'.$i, TextType::class,[
                              'mapped' => false,
                               'required'=>false,
                             ])
                               ->add('maileleve'.$i, EmailType::class,[
                              'mapped' => false,
                              'required'=>false,
                             ])  ->add('genreeleve'.$i, ChoiceType::class,[
                              'mapped' =>false,
                            'choices'=>['F'=>'F',
                                               'M'=>'M']]);            
                         }
             }                                                      
                                                            
          $builder->add('partenaire',TextType::class,[
                              'mapped' =>true,
                              'required'=>false,
                             ])          
                       ->add('contribfinance',ChoiceType::class,[
                              'mapped' =>true,
                            'choices'=>['Prof1'=>1,
                                               'Prof2'=>2,
                                               'Gestionnaire du lycée'=>3,
                                               'Autre'=>4
                              ],
                             
                             ])      
                      ->add('recompense',TextType::class,[
                              'mapped' => true,
                              'required'=>false,
                             ])
                      ->add('origineprojet',TextType::class,[
                              'mapped' => false,
                            
                             ])  
                  ->add('niveau',ChoiceType::class,[
                              'choices'=>['seconde'=>'2nde',
                                                 'première'=>'1ere',
                                                 'terminale'=>'Term',
                              ],
                              'mapped' => true,
                             
                             ])  
                      ->add('save',      SubmitType::class);
       
            
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Equipesadmin::class,'rne'=>null]);
       
    }
}