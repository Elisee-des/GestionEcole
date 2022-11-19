<?php

namespace App\Entity;

use App\Repository\AbscenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbscenceRepository::class)]
class Abscence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $heurAbscence;

    #[ORM\Column(type: 'datetime')]
    private $dateCreationAt;

    #[ORM\ManyToOne(targetEntity: Eleve::class, inversedBy: 'abscences')]
    #[ORM\JoinColumn(nullable: false)]
    private $eleve;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeurAbscence(): ?int
    {
        return $this->heurAbscence;
    }

    public function setHeurAbscence(?int $heurAbscence): self
    {
        $this->heurAbscence = $heurAbscence;

        return $this;
    }

    public function getDateCreationAt(): ?\DateTimeInterface
    {
        return $this->dateCreationAt;
    }

    public function setDateCreationAt(\DateTimeInterface $dateCreationAt): self
    {
        $this->dateCreationAt = $dateCreationAt;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }
}
