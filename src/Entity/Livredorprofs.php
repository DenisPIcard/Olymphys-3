<?php

namespace App\Entity;

use App\Repository\LivredorprofsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * livredorprofs
 * @ORM\Table(name="livresdorprofs")
 * @ORM\Entity(repositoryClass=LivredorprofsRepository::class)
 */
class Livredorprofs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

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
     * @ORM\ManyToOne(targetEntity=User::class)
     *  @ORM\JoinColumn(name="prof_id",  referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $prof;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
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

    public function getProf()
    {
        return $this->prof;
    }

    public function setProf( $prof)
    {
        $this->prof = $prof;

        return $this;
    }
}
