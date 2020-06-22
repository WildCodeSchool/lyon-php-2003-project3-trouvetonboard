<?php

namespace App\Entity;

use App\Repository\EnterpriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EnterpriseRepository::class)
 */
class Enterprise
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
     */
    private $paymentStatus = 0;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
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
     */
    private $websiteLink;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $linkedinLink;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name = 'default';

    /**
     * @ORM\OneToMany(targetEntity=Profile::class, mappedBy="enterprise")
     */
    private $profiles;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="enterprise")
     */
    private $users;

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

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(?int $zipCode): self
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

    public function setName(string $name): self
    {
        $this->name = $name;

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
}
