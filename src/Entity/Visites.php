<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visites
 *
 * @ORM\Table(name="visites")
 * @ORM\Entity(repositoryClass="App\Repository\VisitesRepository")
 */
class Visites
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="intitule", type="string", length=255, nullable=true)
     */
    private $intitule;
    
    
     /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Equipes", inversedBy="visite", cascade={"persist", "remove"})
    * @ORM\JoinColumn(name="equipe_id",nullable=true)
    */
    public $equipe;
    

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set intitule
     *
     * @param string $intitule
     *
     * @return Visites
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    public function getEquipe(): ?Equipes
    {
        return $this->equipe;
    }

    

    public function setEquipe(?Equipes $equipe): self
    {   $equipeini=$this->equipe;
        $this->equipe = $equipe;
   
        if ($equipe==null){
            
        $equipeini->setVisite(null);}
        else{
               
            $equipe->setVisite($this);
            
        }
        return $this;
    }
    
    
    
    
    
}
