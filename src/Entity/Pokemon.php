<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ApiResource()]
#[ORM\Entity]
class Pokemon
{
    //attributes:["pagination_items_per_page" => 10]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "boolean")]
    private ?bool $isManual = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: "integer")]
    private ?int $numeroPokedex = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: "float")]
    private ?float $poids = null;

    #[ORM\Column(type: "float")]
    private ?float $taille = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $type1 = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $type2 = null;

    #[ORM\OneToMany(targetEntity: "Talent", mappedBy: "pokemon", cascade: ["persist", "remove"])]
    private Collection $talents;

    #[ORM\OneToOne(targetEntity: "Statistique", cascade: ["persist", "remove"])]
    private ?Statistique $statistique = null;

    public function __construct()
    {
        $this->talents = new ArrayCollection();
    }

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsManual(): ?bool
    {
        return $this->isManual;
    }

    public function setIsManual(bool $isManual): self
    {
        $this->isManual = $isManual;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getNumeroPokedex(): ?int
    {
        return $this->numeroPokedex;
    }

    public function setNumeroPokedex(int $numeroPokedex): self
    {
        $this->numeroPokedex = $numeroPokedex;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): self
    {
        $this->poids = $poids;
        return $this;
    }

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(float $taille): self
    {
        $this->taille = $taille;
        return $this;
    }

    public function getType1(): ?string
    {
        return $this->type1;
    }

    public function setType1(string $type1): self
    {
        $this->type1 = $type1;
        return $this;
    }

    public function getType2(): ?string
    {
        return $this->type2;
    }

    public function setType2(?string $type2): self
    {
        $this->type2 = $type2;
        return $this;
    }

    public function getTalents(): Collection
    {
        return $this->talents;
    }

    public function addTalent(Talent $talent): self
    {
        if (!$this->talents->contains($talent)) {
            $this->talents[] = $talent;
            $talent->setPokemon($this);
        }

        return $this;
    }

    public function removeTalent(Talent $talent): self
    {
        if ($this->talents->removeElement($talent)) {
            // Set the owning side to null (unless already changed)
            if ($talent->getPokemon() === $this) {
                $talent->setPokemon(null);
            }
        }

        return $this;
    }

    public function getStatistique(): ?Statistique
    {
        return $this->statistique;
    }

    public function setStatistique(?Statistique $statistique): self
    {
        $this->statistique = $statistique;
        return $this;
    }

    public function isManual(): ?bool
    {
        return $this->isManual;
    }

    public function setManual(bool $isManual): static
    {
        $this->isManual = $isManual;

        return $this;
    }
}

