<?php

namespace App\Entity;

use App\Repository\DestinataireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: DestinataireRepository::class)]
class Destinataire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    

    #[Assert\NotBlank(groups: ["creation"])]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le prénom ne doit pas dépasser {{ limit }} caractères.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $prenom = null;
    
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom ne doit pas dépasser {{ limit }} caractères.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;


    #[Assert\NotBlank(message: "L'adresse est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'adresse ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $adresse = null;
    

    #[Assert\NotBlank(message: "Le contact est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le contact ne doit pas dépasser {{ limit }} caractères.",
        )]
        #[Assert\Regex(
            pattern: "/^\d+$/",
            match: true,
            message: "Le contact ne doit contenir que des chiffres.",
            )]
    #[ORM\Column(length: 255)]
    private ?string $contact = null;
    
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;
    

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'destinataire', targetEntity: Transfert::class)]
    private Collection $transferts;

    #[ORM\ManyToOne(inversedBy: 'destinataires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'destinataires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->transferts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Transfert>
     */
    public function getTransferts(): Collection
    {
        return $this->transferts;
    }

    public function addTransfert(Transfert $transfert): static
    {
        if (!$this->transferts->contains($transfert)) {
            $this->transferts->add($transfert);
            $transfert->setDestinataire($this);
        }

        return $this;
    }

    public function removeTransfert(Transfert $transfert): static
    {
        if ($this->transferts->removeElement($transfert)) {
            // set the owning side to null (unless already changed)
            if ($transfert->getDestinataire() === $this) {
                $transfert->setDestinataire(null);
            }
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
