<?php

namespace App\Entity;

use App\Repository\LivredorelevesRepository;
use App\Entity\Equipesadmin;
use App\Entity\Elevesinter;
use App\Entity\Edition;
use Doctrine\ORM\Mapping as ORM;

/**
 * livredoreleves
 * @ORM\Table(name="livresdoreleves")
 * @ORM\Entity(repositoryClass=LivredorelevesRepository::class)
 */
class Livredoreleves
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

  

    /**
     * @ORM\Column(type="text", length=1000)
     */
    private $texte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Edition")
     * @ORM\JoinColumn(name="edition_id",  referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $edition;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Equipesadmin")
     * @ORM\JoinColumn(name="equipe_id",  referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $equipe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $noms;

   

    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getEdition()
    {
        return $this->edition;
    }

    public function setEdition($edition)
    {
        $this->edition = $edition;

        return $this;
    }

    public function getEquipe()
    {
        return $this->equipe;
    }

    public function setEquipe($equipe)
    {
        $this->equipe = $equipe;

        return $this;
    }

    public function getEleve()
    {
        return $this->eleve;
    }

    public function setEleve( $eleve)
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getNoms(): ?string
    {
        return $this->noms;
    }

    public function setNoms(?string $noms): self
    {
        $this->noms = $noms;

        return $this;
    }
}
