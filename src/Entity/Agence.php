<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AgenceRepository::class)]
class Agence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    

    #[Assert\NotBlank(message: "Le pays est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le pays ne doit pas dépasser {{ limit }} caractères.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $pays = null;
    

    #[Assert\NotBlank(message: "La ville est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'La ville ne doit pas dépasser {{ limit }} caractères.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'agences')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libellé): static
    {
        $this->libelle = $libellé;

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
