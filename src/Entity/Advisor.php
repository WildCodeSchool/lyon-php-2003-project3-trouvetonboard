<?php

namespace App\Entity;

use App\Repository\AdvisorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AdvisorRepository::class)
 * @Vich\Uploadable
 */
class Advisor implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAlreadyBoardMember;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $linkedinLink;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $cvLink;

    /**
     * @Vich\UploadableField(mapping="advisors_cv", fileNameProperty="cvLink")
     * @var File|null
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage = "Les fichiers de plus de 1Mo ne sont pas autorisés",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Seul les fichiers PDF sont autorisés"
     * )
     */
    private $cvLinkFile;

    /**
     * @ORM\Column(type="integer")
     */
    private $paymentStatus = 0;

    /**
     * @ORM\OneToMany(targetEntity=Profile::class, mappedBy="advisor")
     */
    private $profiles;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="advisor", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    public function __construct()
    {
        $this->profiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsAlreadyBoardMember(): ?bool
    {
        return $this->isAlreadyBoardMember;
    }

    public function setIsAlreadyBoardMember(?bool $isAlreadyBoardMember): self
    {
        $this->isAlreadyBoardMember = $isAlreadyBoardMember;

        return $this;
    }

    public function getLinkedinLink(): ?string
    {
        return $this->linkedinLink;
    }

    public function setLinkedinLink(?string $linkedinLink): self
    {
        $this->linkedinLink = $linkedinLink;

        return $this;
    }

    public function getCvLink(): ?string
    {
        return $this->cvLink;
    }

    public function setCvLink(?string $cvLink): self
    {
        $this->cvLink = $cvLink;

        return $this;
    }

    /**
     * @param File|UploadedFile|null $file
     */
    public function setCvLinkFile(?File $file = null): void
    {
        $this->cvLinkFile = $file;
        if ($file) {
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getCvLinkFile(): ?File
    {
        return $this->cvLinkFile;
    }


    public function getPaymentStatus(): ?int
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(int $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * @return Collection|Profile[]
     */
    public function getProfiles(): Collection
    {
        return $this->profiles;
    }

    public function addProfile(Profile $profile): self
    {
        if (!$this->profiles->contains($profile)) {
            $this->profiles[] = $profile;
            $profile->setAdvisor($this);
        }

        return $this;
    }

    public function removeProfile(Profile $profile): self
    {
        if ($this->profiles->contains($profile)) {
            $this->profiles->removeElement($profile);
            // set the owning side to null (unless already changed)
            if ($profile->getAdvisor() === $this) {
                $profile->setAdvisor(null);
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

        // set (or unset) the owning side of the relation if necessary
        $newAdvisor = null === $user ? null : $this;
        if ($user && ($user->getAdvisor() !== $newAdvisor)) {
            $user->setAdvisor($newAdvisor);
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt = null): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->cvLink,
        ]);
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        $this->id = unserialize($serialized);
    }
}
