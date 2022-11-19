<?php

namespace App\Entity;

use App\Repository\AnneeScolaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnneeScolaireRepository::class)]
class AnneeScolaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $annee;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\OneToMany(mappedBy: 'anneeScolaire', targetEntity: Trimestre::class, orphanRemoval: true)]
    private $trimestres;

    #[ORM\OneToMany(mappedBy: 'anneeScolaire', targetEntity: Eleve::class, orphanRemoval: true)]
    private $eleves;

    public function __construct()
    {
        $this->trimestres = new ArrayCollection();
        $this->eleves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Trimestre>
     */
    public function getTrimestres(): Collection
    {
        return $this->trimestres;
    }

    public function addTrimestre(Trimestre $trimestre): self
    {
        if (!$this->trimestres->contains($trimestre)) {
            $this->trimestres[] = $trimestre;
            $trimestre->setAnneeScolaire($this);
        }

        return $this;
    }

    public function removeTrimestre(Trimestre $trimestre): self
    {
        if ($this->trimestres->removeElement($trimestre)) {
            // set the owning side to null (unless already changed)
            if ($trimestre->getAnneeScolaire() === $this) {
                $trimestre->setAnneeScolaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Eleve>
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addElefe(Eleve $elefe): self
    {
        if (!$this->eleves->contains($elefe)) {
            $this->eleves[] = $elefe;
            $elefe->setAnneeScolaire($this);
        }

        return $this;
    }

    public function removeElefe(Eleve $elefe): self
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getAnneeScolaire() === $this) {
                $elefe->setAnneeScolaire(null);
            }
        }

        return $this;
    }
}
