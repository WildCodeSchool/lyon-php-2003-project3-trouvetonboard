<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 */
class Profile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRequest;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPropose;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paymentType;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity=Skill::class, inversedBy="profiles")
     */
    private $skills;

    /**
     * @ORM\ManyToOne(targetEntity=Enterprise::class, inversedBy="profiles")
     */
    private $enterprise;

    /**
     * @ORM\ManyToOne(targetEntity=Advisor::class, inversedBy="profiles")
     */
    private $advisor;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\ManyToMany(targetEntity=Profile::class, inversedBy="advisorProfiles")
     */
    private $enterpriseProfiles;

    /**
     * @ORM\ManyToMany(targetEntity=Profile::class, mappedBy="enterpriseProfiles")
     */
    private $advisorProfiles;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $archived;


    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->enterpriseProfiles = new ArrayCollection();
        $this->advisorProfiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsRequest(): ?bool
    {
        return $this->isRequest;
    }

    public function setIsRequest(bool $isRequest): self
    {
        $this->isRequest = $isRequest;

        return $this;
    }

    public function getIsPropose(): ?bool
    {
        return $this->isPropose;
    }

    public function setIsPropose(bool $isPropose): self
    {
        $this->isPropose = $isPropose;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    public function setPaymentType(string $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|Skill[]
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        if ($this->skills->contains($skill)) {
            $this->skills->removeElement($skill);
        }

        return $this;
    }

    public function getEnterprise(): ?Enterprise
    {
        return $this->enterprise;
    }

    public function setEnterprise(?Enterprise $enterprise): self
    {
        $this->enterprise = $enterprise;

        return $this;
    }

    public function getAdvisor(): ?Advisor
    {
        return $this->advisor;
    }

    public function setAdvisor(?Advisor $advisor): self
    {
        $this->advisor = $advisor;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getEnterpriseProfiles(): Collection
    {
        return $this->enterpriseProfiles;
    }

    public function addEnterpriseProfile(self $enterpriseProfile): self
    {
        if (!$this->enterpriseProfiles->contains($enterpriseProfile)) {
            $this->enterpriseProfiles[] = $enterpriseProfile;
        }

        return $this;
    }

    public function removeEnterpriseProfile(self $enterpriseProfile): self
    {
        if ($this->enterpriseProfiles->contains($enterpriseProfile)) {
            $this->enterpriseProfiles->removeElement($enterpriseProfile);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getAdvisorProfiles(): Collection
    {
        return $this->advisorProfiles;
    }

    public function addAdvisorProfile(self $advisorProfile): self
    {
        if (!$this->advisorProfiles->contains($advisorProfile)) {
            $this->advisorProfiles[] = $advisorProfile;
            $advisorProfile->addEnterpriseProfile($this);
        }

        return $this;
    }

    public function removeAdvisorProfile(self $advisorProfile): self
    {
        if ($this->advisorProfiles->contains($advisorProfile)) {
            $this->advisorProfiles->removeElement($advisorProfile);
            $advisorProfile->removeEnterpriseProfile($this);
        }

        return $this;
    }

    /**
     * @param \App\Entity\Skill $skill
     *
     * @return bool
     */
    public function isInSkillList(Skill $skill)
    {

        if ($this->skills->contains($skill)) {
            return true;
        }

        return false;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(?bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }
}
