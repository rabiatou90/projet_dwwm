<?php

namespace App\Entity;

use App\Repository\TransfertRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: TransfertRepository::class)]
class Transfert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Assert\NotBlank(message: "Le montant du transfert est obligatoire.")]
    #[ORM\Column]
    private ?float $montant_du_transfert = null;
    
    #[Assert\NotBlank(message: "Les frais d'envoi sont obligatoires.")]
    #[ORM\Column]
    private ?float $frais_envoie = null;
    

    #[Assert\NotBlank(message: "Le montant reçu est obligatoire.")]
    #[ORM\Column]
    private ?float $montant_recu = null;
    
    
    #[ORM\Column(length: 255)]
    private ?string $mode_de_retrait = null;
    
    #[Assert\NotNull(message: "Le code de transfert ne doit pas être vide.")]
    #[ORM\Column(length:255)]
    private ?string $code_de_transfert = null;
    
    
    #[Assert\NotBlank(message: "Le moyen de paiement est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le moyen de paiement ne doit pas dépasser {{ limit }} caractères.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $moyen_de_paiement = null;
    
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'transferts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'transferts')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'transferts')]
    private ?Destinataire $destinataire = null;

    public function getId(): ?int
    {

        return $this->id;
    }

    public function getMontantDuTransfert(): ?float
    {
        return $this->montant_du_transfert;
    }

    public function setMontantDuTransfert(float $montant_du_transfert): static
    {
        $this->montant_du_transfert = $montant_du_transfert;

        return $this;
    }

    public function getFraisEnvoie(): ?float
    {
        return $this->frais_envoie;
    }

    public function setFraisEnvoie(float $frais_envoie): static
    {
        $this->frais_envoie = $frais_envoie;

        return $this;
    }

    public function getMontantRecu(): ?float
    {
        return $this->montant_recu;
    }

    public function setMontantRecu(float $montant_recu): static
    {
        $this->montant_recu = $montant_recu;

        return $this;
    }

    public function getModeDeRetrait(): ?string
    {
        return $this->mode_de_retrait;
    }

    public function setModeDeRetrait(string $mode_de_retrait): static
    {
        $this->mode_de_retrait = $mode_de_retrait;

        return $this;
    }

    public function getCodeDeTransfert(): ?string
    {
        return $this->code_de_transfert;
    }

    public function setCodeDeTransfert(string $code_de_transfert): static
    {
        $this->code_de_transfert = $code_de_transfert;

        return $this;
    }

    public function getMoyenDePaiement(): ?string
    {
        return $this->moyen_de_paiement;
    }

    public function setMoyenDePaiement(string $moyen_de_paiement): static
    {
        $this->moyen_de_paiement = $moyen_de_paiement;

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

    public function getDestinataire(): ?Destinataire
    {
        return $this->destinataire;
    }

    public function setDestinataire(?Destinataire $destinataire): static
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    
    
    public function generateCodeDeTransfert(): void
    {
        // Génération du code unique seulement si le champ est actuellement null
        if ($this->code_de_transfert === null) {
            $this->code_de_transfert = strtoupper(bin2hex(random_bytes(16)));
        }
    }
}
