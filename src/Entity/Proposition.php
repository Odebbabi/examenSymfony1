<?php

namespace App\Entity;

use App\Repository\PropositionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropositionRepository::class)
 */
class Proposition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomProposition;

    /**
     * @ORM\Column(type="boolean")
     */
    private $correcte;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="proposition")
     */
    private $question;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProposition(): ?string
    {
        return $this->nomProposition;
    }

    public function setNomProposition(string $nomProposition): self
    {
        $this->nomProposition = $nomProposition;

        return $this;
    }

    public function getCorrecte(): ?bool
    {
        return $this->correcte;
    }

    public function setCorrecte(bool $correcte): self
    {
        $this->correcte = $correcte;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }


}
