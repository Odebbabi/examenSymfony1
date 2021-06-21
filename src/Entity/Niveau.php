<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
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
    private $nomNiveau;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $numNiveau;

    /**
     * @ORM\OneToMany(targetEntity=Matiere::class, mappedBy="niveau")
     */
    private $matieres;

    public function __construct()
    {
        $this->matieres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomNiveau(): ?string
    {
        return $this->nomNiveau;
    }

    public function setNomNiveau(string $nomNiveau): self
    {
        $this->nomNiveau = $nomNiveau;

        return $this;
    }

    public function getNumNiveau(): ?int
    {
        return $this->numNiveau;
    }

    public function setNumNiveau(int $numNiveau): self
    {
        $this->numNiveau = $numNiveau;

        return $this;
    }

    /**
     * @return Collection|Matiere[]
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres[] = $matiere;
            $matiere->setNiveau($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        if ($this->matieres->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getNiveau() === $this) {
                $matiere->setNiveau(null);
            }
        }

        return $this;
    }
    /**
     * Transform to string
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}
