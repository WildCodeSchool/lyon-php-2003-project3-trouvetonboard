<?php

namespace App\Entity;

use App\Repository\AdvisorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdvisorRepository::class)
 */
class Advisor
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
     * @ORM\Column(type="integer")
     */
    private $paymentStatus;

    /**
     * @ORM\OneToMany(targetEntity=Profile::class, mappedBy="advisor")
     */
    private $profiles;

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
}
