<?php

namespace App\Form;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType ; 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Entity\Memoires;
use App\Entity\Equipes;
use App\Entity\Totalequipes;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\TypeEntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ToutfichiersType extends AbstractType
{   public function __construct(SessionInterface $session)
        {
            $this->session = $session;
        }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {       
       
      
         
        $builder
            // ...
          ->add('fichier', FileType::class, [
                                'label' => 'Choisir le fichier ',
                          'mapped' => false,

              
                'required' => false,
                
                     
              
              ]);
         if (($options['data']['role']== 'ROLE_ORGACIA') or ($options['data']['role']== 'ROLE_COMITE') ){
               if ($options['data']['choix']!='autorisations_photos'){
            $builder->add('typefichier', ChoiceType::class, [
                            'mapped'=>false,
                            'required' => false,
                            'multiple'=>false,
                            'choices' => [
                                'Mémoire(pdf, 2,5 M max, 20 pages)'=>0,
                                'Annexe(pdf, 2,5 M max  20 pages)'=>1,
                                'Résumé(pdf, 1 M max, 1 page)'=>2,
                                'Fiche sécurité(1M max, doc, docx, pdf, jpg, odt)'=>4,
                               // 'Présentation du concours national(pdf, 10 M max)'=>3,
                                'Diaporama  pour le jury(pdf, 10 M maxi)' =>3
                               ]
                               ] );
               }
               if ($options['data']['choix']=='autorisation_photos'){
              $builder->add('typefichier', ChoiceType::class, [
                            'mapped'=>false,
                            'required' => false,
                            'multiple'=>false,
                   'empty_data' =>  '6',
                               'placeholder' =>  'Autorisations photos (pdf, 1M max )',
                            'choices' => [
                               'Autorisations photos (pdf,1M max )'=>6,
                               ]
                               ] );
                         }
            
            
            
        }
        
        
        
        else {
        if ($options['data']['choix']!='diaporama_jury'){
             if ($options['data']['choix']!='autorisations_photos'){
              $builder->add('typefichier', ChoiceType::class, [
                            'mapped'=>false,
                            'required' => false,
                            'multiple'=>false,
                            'choices' => [
                                'Mémoire(pdf, 2,5 M max, 20 pages)'=>0,
                                'Annexe(pdf, 2,5 M max  20 pages)'=>1,
                                'Résumé(pdf, 1 M max, 1 page)'=>2,
                                'Fiche sécurité(1M max, doc, docx, pdf, jpg, odt)'=>4,
                               // 'Présentation du concours national(pdf, 10 M max)'=>3,
                                
                               ]
                               ] );
                        }
              if ($options['data']['choix']=='autorisation_photos'){
              $builder->add('typefichier', ChoiceType::class, [
                            'mapped'=>false,
                            'required' => false,
                            'multiple'=>false,
                   'empty_data' =>  '6',
                               'placeholder' =>  'Autorisations photos (pdf, 1M max )',
                            'choices' => [
                               'Autorisations photos (pdf,1M max )'=>6,
                               ]
                               ] );
                         }
           }
           else{
                 if ($options['data']['choix']=='diaporama_jury'){
                           $builder->add('typefichier', ChoiceType::class, [
                            'mapped'=>false,
                            'required' => false,
                                'multiple'=>false,
                               'empty_data' =>  '3',
                               'placeholder' =>  'Diaporama pour le jury(pdf, 10 M maxi)',
                            'choices' => [
                               
                                'Diaporama pour le jury(pdf, 10 M maxi)' =>3]
                               ] ) ;  
              
             }
           }
        }
              $builder->add('save',      SubmitType::class);
       
            
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => null, 'choix'=>null,'role'=>null]);
    }
}