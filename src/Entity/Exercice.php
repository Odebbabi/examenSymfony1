<?php

namespace App\Entity;

use App\Repository\ExerciceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;

use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ExerciceRepository::class)
 * @Vich\Uploadable
 */
class Exercice
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
    private $typeExercice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomExercice;

    /**
     * @ORM\ManyToOne(targetEntity=Cour::class, inversedBy="exercices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cour;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="exercice", fileNameProperty="pdf.name", mimeType="pdf.mimeType", originalName="pdf.originalName", dimensions="pdf.dimensions")
     *
     * @var File|null
     */
    private $filePDF;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     *
     * @var EmbeddedFile
     */
    private $pdf;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="exercice", fileNameProperty="correction.name", mimeType="correction.mimeType", originalName="correction.originalName", dimensions="correction.dimensions")
     *
     * @var File|null
     */
    private $correctionPDF;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     *
     * @var EmbeddedFile
     */
    private $correction;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="exercice", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="exercices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->pdf = new EmbeddedFile();
        $this->correction = new EmbeddedFile();
        $this->comments = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeExercice(): ?string
    {
        return $this->typeExercice;
    }

    public function setTypeExercice(string $typeExercice): self
    {
        $this->typeExercice = $typeExercice;

        return $this;
    }

    public function getCour(): ?Cour
    {
        return $this->cour;
    }

    public function setCour(?Cour $cour): self
    {
        $this->cour = $cour;

        return $this;
    }

    public function getNomExercice(): ?string
    {
        return $this->nomExercice;
    }

    public function setNomExercice(string $nomExercice): self
    {
        $this->nomExercice = $nomExercice;

        return $this;
    }



    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile|null $filePDF
     */
    public function setFilePDF(?File $filePDF = null)
    {
        $this->filePDF = $filePDF;

        if (null !== $filePDF) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFilePDF(): ?File
    {
        return $this->filePDF;
    }

    public function setPdf(EmbeddedFile $pdf): void
    {
        $this->pdf = $pdf;
    }

    public function getPdf(): ?EmbeddedFile
    {
        return $this->pdf;
    }


    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (! in_array($this->filePDF->getMimeType(), array(
            'image/jpeg',
            'image/gif',
            'image/png',
            'video/mp4',
            'video/quicktime',
            'video/avi',
            'application/pdf'
        ))) {
            $context
                ->buildViolation('Wrong file type (jpg,gif,png,mp4,mov,avi)')
                ->atPath('fileName')
                ->addViolation()
            ;
        }

        if (! in_array($this->correctionPDF->getMimeType(), array(
            'image/jpeg',
            'image/gif',
            'image/png',
            'video/mp4',
            'video/quicktime',
            'video/avi',
            'application/pdf'
        ))) {
            $context
                ->buildViolation('Wrong file type (jpg,gif,png,mp4,mov,avi)')
                ->atPath('fileName')
                ->addViolation()
            ;
        }

    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile|null $correctionPDF
     */
    public function setCorrectionPDF(?File $correctionPDF = null)
    {
        $this->correctionPDF = $correctionPDF;

        if (null !== $correctionPDF) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getCorrectionPDF(): ?File
    {
        return $this->correctionPDF;
    }

    public function setCorrection(EmbeddedFile $correction): void
    {
        $this->correction = $correction;
    }

    public function getCorrection(): ?EmbeddedFile
    {
        return $this->correction;
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
            $comment->setExercice($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getExercice() === $this) {
                $comment->setExercice(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
