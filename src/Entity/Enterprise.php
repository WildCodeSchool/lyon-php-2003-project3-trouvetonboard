<?php

namespace App\Entity;

use App\Repository\EnterpriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass=EnterpriseRepository::class)
 * @Vich\Uploadable()
 */
class Enterprise implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message="Le staus de paiement ne peut pas etre inférieur a 0."
     * )
     * @Assert\LessThanOrEqual(
     *     value = 5,
     *     message="Le staus de paiement ne peut pas etre supérieur a 5."
     * )
     */
    private $paymentStatus = 0;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     * @Assert\NotBlank
     */
    private $legelRepresentative;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     * @Assert\Length(
     *      min = 5,
     *      max = 300,
     *      minMessage = "Le lien ne peux pas inférieur a {{ limit }} caractères",
     *      maxMessage = "Le lien ne peux pas depasser {{ limit }} caracterès",
     * )
     * @Assert\Url(message = "L'url '{{ value }}' n'est pas une url valide.")
     */
    private $websiteLink;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     * @Assert\Length(
     *      min = 5,
     *      max = 300,
     *      minMessage = "Le lien ne peux pas inférieur a {{ limit }} caractères",
     *      maxMessage = "Le lien ne peux pas depasser {{ limit }} caracterès",
     * )
     * @Assert\Url(message = "L'url '{{ value }}' n'est pas une url valide.")
     */
    private $linkedinLink;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *     message="Le nom de l'entreprise ne peut être vide."
     * )
     * @Assert\NotNull(
     *     message="Le nom de l'entreprise ne peut être vide."
     * )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Profile::class, mappedBy="enterprise")
     */
    private $profiles;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="enterprise")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brochure;

    /**
     * @Vich\UploadableField(mapping="enterprises_brochure", fileNameProperty="brochure")
     * @var File|null
     * @Assert\File(
     *     maxSize="12M",
     *     maxSizeMessage="Les fichiers de plus de 12Mo ne sont pas autorisés",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Seul les fichiers PDF sont autorisés"
     * )
     */
    private $brochureFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    public function __construct()
    {
        $this->profiles = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLegelRepresentative(): ?string
    {
        return $this->legelRepresentative;
    }

    public function setLegelRepresentative(?string $legelRepresentative): self
    {
        $this->legelRepresentative = $legelRepresentative;

        return $this;
    }

    public function getWebsiteLink(): ?string
    {
        return $this->websiteLink;
    }

    public function setWebsiteLink(?string $websiteLink): self
    {
        $this->websiteLink = $websiteLink;

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

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        if ($name) {
            $this->name = $name;
        } else {
            $this->name = ' ';
        }


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
            $profile->setEnterprise($this);
        }

        return $this;
    }

    public function removeProfile(Profile $profile): self
    {
        if ($this->profiles->contains($profile)) {
            $this->profiles->removeElement($profile);
            // set the owning side to null (unless already changed)
            if ($profile->getEnterprise() === $this) {
                $profile->setEnterprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setEnterprise($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getEnterprise() === $this) {
                $user->setEnterprise(null);
            }
        }

        return $this;
    }

    public function __toString() : string
    {
        $val = $this->getName();
        if ($val) {
            return $val;
        } else {
            return "";
        }
    }

    public function getBrochure(): ?string
    {
        return $this->brochure;
    }

    public function setBrochure(?string $brochure): self
    {
        $this->brochure = $brochure;

        return $this;
    }

    /**
     * @param File|UploadedFile|null $file
     */
    public function setBrochureFile(?File $file = null): void
    {
        $this->brochureFile = $file;
        if ($file) {
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getBrochureFile(): ?File
    {
        return $this->brochureFile;
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
            $this->brochure,
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
