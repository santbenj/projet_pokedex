<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: "App\Repository\StatistiqueRepository")]
class Statistique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "integer")]
    private ?int $hp = null;

    #[ORM\Column(type: "integer")]
    private ?int $attack = null;

    #[ORM\Column(type: "integer")]
    private ?int $defense = null;

    #[ORM\Column(type: "integer")]
    private ?int $specialAttack = null;

    #[ORM\Column(type: "integer")]
    private ?int $specialDefense = null;

    #[ORM\Column(type: "integer")]
    private ?int $speed = null;

    #[ORM\OneToOne(targetEntity: "Pokemon", mappedBy: "statistique")]
    private ?Pokemon $pokemon = null;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(int $hp): self
    {
        $this->hp = $hp;
        return $this;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(int $attack): self
    {
        $this->attack = $attack;
        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): self
    {
        $this->defense = $defense;
        return $this;
    }

    public function getSpecialAttack(): ?int
    {
        return $this->specialAttack;
    }

    public function setSpecialAttack(int $specialAttack): self
    {
        $this->specialAttack = $specialAttack;
        return $this;
    }

    public function getSpecialDefense(): ?int
    {
        return $this->specialDefense;
    }

    public function setSpecialDefense(int $specialDefense): self
    {
        $this->specialDefense = $specialDefense;
        return $this;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(int $speed): self
    {
        $this->speed = $speed;
        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;
        return $this;
    }
}
