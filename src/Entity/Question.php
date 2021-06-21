<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
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
    private $nomQuestion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;



    /**
     * @ORM\OneToMany(targetEntity=Proposition::class, mappedBy="question", cascade={"persist"})
     */
    private $proposition;

    /**
     * @ORM\ManyToMany(targetEntity=Quiz::class, mappedBy="question")
     */
    private $quiz;

    public function __construct()
    {
        $this->proposition = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->quiz = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomQuestion(): ?string
    {
        return $this->nomQuestion;
    }

    public function setNomQuestion(string $nomQuestion): self
    {
        $this->nomQuestion = $nomQuestion;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }



    /**
     * @return Collection|Proposition[]
     */
    public function getProposition(): Collection
    {
        return $this->proposition;
    }

    public function addProposition(Proposition $proposition): self
    {
        if (!$this->proposition->contains($proposition)) {
            $this->proposition[] = $proposition;
            $proposition->setQuestion($this);
        }

        return $this;
    }

    public function removeProposition(Proposition $proposition): self
    {
        if ($this->proposition->removeElement($proposition)) {
            // set the owning side to null (unless already changed)
            if ($proposition->getQuestion() === $this) {
                $proposition->setQuestion(null);
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

    /**
     * @return Collection|Quiz[]
     */
    public function getQuiz(): Collection
    {
        return $this->quiz;
    }

    public function addQuiz(Quiz $quiz): self
    {
        if (!$this->quiz->contains($quiz)) {
            $this->quiz[] = $quiz;
            $quiz->addQuestion($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): self
    {
        if ($this->quiz->removeElement($quiz)) {
            $quiz->removeQuestion($this);
        }

        return $this;
    }
}
