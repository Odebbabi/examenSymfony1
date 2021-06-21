<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="Un utilisateur existe déjà avec cette adresse email.")
 * @Vich\Uploadable
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\Column(type="date")
     */
    private $dateBirth;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $niveau;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numCarteInscription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $specialty;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numCin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accountType;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="user", fileNameProperty="carteInscription")
     *
     * @var File|null
     */
    private $imageCarteInscription;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $carteInscription;


    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="user", fileNameProperty="cinAvant")
     *
     * @var File|null
     */
    private $imageCinAvant;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $cinAvant;


    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $cinAvantUpdatedAt;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="user", fileNameProperty="cinArriere")
     *
     * @var File|null
     */
    private $imageCinArriere;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $cinArriere;


    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $cinArriereUpdatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Cour::class, mappedBy="user", orphanRemoval=true)
     */
    private $cours;

    /**
     * @ORM\OneToMany(targetEntity=Exercice::class, mappedBy="user", orphanRemoval=true)
     */
    private $exercices;

    /**
     * @ORM\OneToMany(targetEntity=Examen::class, mappedBy="user", orphanRemoval=true)
     */
    private $examens;

    /**
     * @ORM\OneToMany(targetEntity=Quiz::class, mappedBy="user", orphanRemoval=true)
     */
    private $quizzes;




    public function __construct()
    {
        $this->updatedAt = new \DateTime();
        $this->cinAvantUpdatedAt = new \DateTime();
        $this->cinArriereUpdatedAt = new \DateTime();
        $this->comments = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->exercices = new ArrayCollection();
        $this->examens = new ArrayCollection();
        $this->quizzes = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getDateBirth(): ?\DateTimeInterface
    {
        return $this->dateBirth;
    }

    public function setDateBirth(\DateTimeInterface $dateBirth): self
    {
        $this->dateBirth = $dateBirth;

        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(?int $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getNumCarteInscription(): ?string
    {
        return $this->numCarteInscription;
    }

    public function setNumCarteInscription(?string $numCarteInscription): self
    {
        $this->numCarteInscription = $numCarteInscription;

        return $this;
    }

    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(?string $specialty): self
    {
        $this->specialty = $specialty;

        return $this;
    }

    public function getNumCin(): ?int
    {
        return $this->numCin;
    }

    public function setNumCin(?int $numCin): self
    {
        $this->numCin = $numCin;

        return $this;
    }

    public function getAccountType(): ?string
    {
        return $this->accountType;
    }

    public function setAccountType(string $accountType): self
    {
        $this->accountType = $accountType;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageCarteInscription
     */
    public function setImageCarteInscription(?File $imageCarteInscription = null): void
    {
        $this->imageCarteInscription = $imageCarteInscription;

        if (null !== $imageCarteInscription) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageCarteInscription(): ?File
    {
        return $this->imageCarteInscription;
    }

    public function setCarteInscription(?string $carteInscription): void
    {
        $this->carteInscription = $carteInscription;
    }

    public function getCarteInscription(): ?string
    {
        return $this->carteInscription;
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageCinAvant
     */
    public function setImageCinAvant(?File $imageCinAvant = null): void
    {
        $this->imageCinAvant = $imageCinAvant;

        if (null !== $imageCinAvant) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->cinAvantUpdatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageCinAvant(): ?File
    {
        return $this->imageCinAvant;
    }

    public function setCinAvant(?string $cinAvant): void
    {
        $this->cinAvant = $cinAvant;
    }

    public function getCinAvant(): ?string
    {
        return $this->cinAvant;
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageCinArriere
     */
    public function setImageCinArriere(?File $imageCinArriere = null): void
    {
        $this->imageCinArriere = $imageCinArriere;

        if (null !== $imageCinArriere) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->cinArriereUpdatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageCinArriere(): ?File
    {
        return $this->imageCinArriere;
    }

    public function setCinArriere(?string $cinArriere): void
    {
        $this->cinArriere = $cinArriere;
    }

    public function getCinArriere(): ?string
    {
        return $this->cinArriere;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
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
     * @return Collection|Cour[]
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cour $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours[] = $cour;
            $cour->setUser($this);
        }

        return $this;
    }

    public function removeCour(Cour $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getUser() === $this) {
                $cour->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Exercice[]
     */
    public function getExercices(): Collection
    {
        return $this->exercices;
    }

    public function addExercice(Exercice $exercice): self
    {
        if (!$this->exercices->contains($exercice)) {
            $this->exercices[] = $exercice;
            $exercice->setUser($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): self
    {
        if ($this->exercices->removeElement($exercice)) {
            // set the owning side to null (unless already changed)
            if ($exercice->getUser() === $this) {
                $exercice->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Examen[]
     */
    public function getExamens(): Collection
    {
        return $this->examens;
    }

    public function addExamen(Examen $examen): self
    {
        if (!$this->examens->contains($examen)) {
            $this->examens[] = $examen;
            $examen->setUser($this);
        }

        return $this;
    }

    public function removeExamen(Examen $examen): self
    {
        if ($this->examens->removeElement($examen)) {
            // set the owning side to null (unless already changed)
            if ($examen->getUser() === $this) {
                $examen->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Quiz[]
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): self
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes[] = $quiz;
            $quiz->setUser($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): self
    {
        if ($this->quizzes->removeElement($quiz)) {
            // set the owning side to null (unless already changed)
            if ($quiz->getUser() === $this) {
                $quiz->setUser(null);
            }
        }

        return $this;
    }


}
