<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;




/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Cet email est déjà enregistré en base.")
 * @UniqueEntity(fields="username", message="Cet identifiant est déjà enregistré en base")
 */
class User implements UserInterface, \Serializable
{


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     */
    private string $username;

     /**
     * * @var array
     * @ORM\Column(type="array")
     */
    private array $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @var string The hashed password
     */
    private string $plainPassword;
    
     /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=60)
     * @Assert\Email()
     */
    private string $email;
 
     /**
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private bool $isActive;
    
    /**
     * @var string le token qui servira lors de l'oubli de mot de passe
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $token;
    
     /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private \DateTime $passwordRequestedAt;
    
    /**
     * @var string
     *
     * @ORM\Column(name="rne", type="string", length=255, nullable=true)
     */
    protected string $rne;
    
     /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    protected string $nom;
    
     /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    protected string $prenom;
    
     /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    protected string $adresse;
    
     /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    protected string $ville;
    
     /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=11, nullable=true)
     */
    protected string $code;
    
     /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=15, nullable=true)
     */
    protected string $phone;
   
     /**
       *  
       * @ORM\ManyToOne(targetEntity="App\Entity\Centrescia")
       * @ORM\JoinColumn(name="centre_id",  referencedColumnName="id" )
       */       
    private $centrecia;
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private \DateTime $createdAt;
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private \DateTime $updatedAt;
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastVisit", type="datetime", nullable=true)
     */
    private \DateTime $lastVisit;
    
     /**
     * @var string
     *
     * @ORM\Column(name="civilite", type="string", length=15, nullable=true)
     */
    protected string $civilite;
    

    /**
       *  
       * @ORM\OneToOne(targetEntity="App\Entity\Fichiersequipes", cascade={"persist"})
       * @ORM\JoinColumn( referencedColumnName="id", )
       */
     private $autorisationphotos;

     /**
      * @ORM\OneToMany(targetEntity=Equipes::class, mappedBy="hote")
      */
     private $interlocuteur;

    /**
     * @ORM\ManyToOne(targetEntity=rne::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?rne $rneId;


    public function __construct(EntityManager $em)
    {
        $this->isActive = true;
        $this->roles = ['ROLE_USER'];
        $this->em = $em;
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }
   
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    
     /*
     * Get email
     */
    public function getCentrecia()
    {
        return $this->centrecia;
    }
 
    /*
     * Set email
     */
    public function setCentrecia($centrecia): User
    {
        $this->centrecia= $centrecia;
        return $this;
    }
    

    /*
     * Get email
     */
    public function getEmail()
    {
        return $this->email;
    }
 
    /*
     * Set email
     */
    public function setEmail($email): User
    {
        $this->email = $email;
        return $this;
    }
 
    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
    
    /**
     * @param string $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }  
    
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles; 
    }

    public function setRoles(array $roles): User
    {
        if (!in_array('ROLE_USER', $roles))
        {
            $roles[] = 'ROLE_USER';
        }
        foreach ($roles as $role)
        {
            if(substr($role, 0, 5) !== 'ROLE_') {
                throw new InvalidArgumentException("Chaque rôle doit commencer par 'ROLE_'");
            }
        }
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    /*
     * Get isActive
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }
 
    /*
     * Set isActive
     */
    public function setIsActive($isActive): User
    {
        $this->isActive = $isActive;
        return $this;
    }
    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    
    /*
     * Get passwordRequestedAt
     */
    public function getPasswordRequestedAt(): \DateTime
    {
        return $this->passwordRequestedAt;
    }

    /*
     * Set passwordRequestedAt
     */
    public function setPasswordRequestedAt($passwordRequestedAt): User
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
        return $this;
    }
    
    /** @see \Serializable::serialize() */
    public function serialize(): ?string
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            // voir remarques sur salt plus haut
            // $this->salt,
        ));
    }
 
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            // voir remarques sur salt plus haut
            // $this->salt
        ) = unserialize($serialized);
    }
    
        /**
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(max=4096)
     */
 
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
 
    protected function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }
    
     /**
     * Set rne
     *
     * @param string $rne
     *
     * @return User
     */
    public function setRne( $rne): User
    {
        $this->rne= $rne;

        return $this;
    }

    /**
     * Get Adresse
     *
     * @return string
     */
    public function getAdresse(): string
    {
        return $this->adresse;
    }
    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return User
     */
    public function setAdresse( $adresse): User
    {
        $this->adresse= $adresse;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille(): string
    {
        return $this->ville;
    }
    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return User
     */
    public function setVille( $ville): User
    {
        $this->ville= $ville;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
    /**
     * Set Code
     *
     * @param string $code
     *
     * @return User
     */
    public function setCode( $code): User
    {
        $this->code= $code;

        return $this;
    }
    
    /**
     * Get 
     *
     * @return string
     */
    public function getCivilite(): string
    {
        return $this->civilite;
    }
    /**
     * Set civilite
     *
     * @param string $civilite
     *
     * @return User
     */
    public function setCivilite( $civilite): User
    {
        $this->civilite= $civilite;

        return $this;
    }
     /**
     * Get phone
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Set phone
     *
     * @param $phone
     * @return User
     */
    public function setPhone( $phone): User
    {
        $this->phone= $phone;

        return $this;
    }

    /**
     * Get rne
     *
     * @return string
     */
    public function getRne(): string
    {
        return $this->rne;
    }
    
    /**
     * Get nom
     *
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }
    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom(string $nom): User
    {
        $this->nom= $nom;

        return $this;
    }

    
    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }
    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom( $prenom): User
    {
        $this->prenom= $prenom;

        return $this;
    }

     /*
     * Get createdAt
     */
    public function getCreatedAt()
    {
        return $this->creaatedAt;
    }

    /*
     * Set updatedAt
     */
    public function setCreatedAt($createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    /*
     * Get updatedAt
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /*
     * Set updatedAt
     */
    public function setUpdatedAt($updatedAt): User
    {
        $this->updatedAt =$updatedAt;
        return $this;
    }
    
     /* Get lastVisit
     */
    public function getLastVisit(): \DateTime
    {
        return $this->lastVisit;
    }

    /*
     * Set lastVisit
     */
    public function setLastVisit($lastVisit): User
    {
        $this->lastVisit = $lastVisit;
        return $this;
    }
     public function getAutorisationphotos()
    {
        return $this->autorisationphotos;
    }
    
    
    public function setAutorisationphotos($autorisation): User
    {
        $this->autorisationphotos = $autorisation;

        return $this;
    }
    public function getNomPrenom(): string
    {
        return $this->nom.' '.$this->prenom;
        
    }
    
     public function getPrenomNom(): string
     {
        return $this->prenom.' '.$this->nom;
        
    }

    /**
     * @return Collection|Equipes[]
     */
    public function getInterlocuteur(): Collection
    {
        return $this->interlocuteur;
    }

    public function addInterlocuteur(Equipes $interlocuteur): self
    {
        if (!$this->interlocuteur->contains($interlocuteur)) {
            $this->interlocuteur[] = $interlocuteur;
            $interlocuteur->setHote($this);
        }

        return $this;
    }

    public function removeInterlocuteur(Equipes $interlocuteur): self
    {
        if ($this->interlocuteur->removeElement($interlocuteur)) {
            // set the owning side to null (unless already changed)
            if ($interlocuteur->getHote() === $this) {
                $interlocuteur->setHote(null);
            }
        }

        return $this;
    }

    public function getRneId(): ?rne
    {
        return $this->rneId;
    }

    public function setRneId(?rne $rneId): self
    {
        $this->rneId = $rneId;

        return $this;
    }



}
