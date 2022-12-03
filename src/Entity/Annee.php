<?php

namespace App\Entity;

use App\Repository\AnneeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnneeRepository::class)
 */
class Annee
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Trimestre::class, mappedBy="annee", orphanRemoval=true)
     */
    private $trimestres;

    /**
     * @ORM\OneToMany(targetEntity=Eleve::class, mappedBy="annee", orphanRemoval=true)
     */
    private $eleves;

    public function __construct()
    {
        $this->trimestres = new ArrayCollection();
        $this->eleves = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $trimestre->setAnnee($this);
        }

        return $this;
    }

    public function removeTrimestre(Trimestre $trimestre): self
    {
        if ($this->trimestres->removeElement($trimestre)) {
            // set the owning side to null (unless already changed)
            if ($trimestre->getAnnee() === $this) {
                $trimestre->setAnnee(null);
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
            $elefe->setAnnee($this);
        }

        return $this;
    }

    public function removeElefe(Eleve $elefe): self
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getAnnee() === $this) {
                $elefe->setAnnee(null);
            }
        }

        return $this;
    }
}
