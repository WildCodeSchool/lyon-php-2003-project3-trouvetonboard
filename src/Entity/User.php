<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cet email est deja utilisé, merci de le modifier"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Merci de saisir une valeur")
     * @Assert\Email(strict=true, message="Le format de l'email est incorrect")
     * @Assert\Email(checkMX=true, message="Aucun serveur mail n'a été trouvé pour ce domaine")
     * @Assert\Length(min=5)
     */
    private $email = '';

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Merci de saisir un mot de passe")
     * @Assert\NotNull(message="Merci de saisir un mot de passe")
     * @Assert\Length(min="8", max="255", minMessage="Votre mot de passe doit comporter au minimum {{ limit }}
     * caractères.", maxMessage="Votre mot de passe doit comporter au maximum {{ limit }} caractères.")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Merci de renseigner votre prénom")
     * @Assert\NotNull()
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Il ne peut y avoir de nombre dans votre prénom"
     * )
     * @Assert\Regex(
     *     pattern="/^(\s)+/",
     *     match=false,
     *     message="Il ne peut y avoir d'espaces dans votre prénom'"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Merci de renseigner votre nom")
     * @Assert\NotNull()
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Il ne peut y avoir de nombre dans votre nom"
     * )
     * @Assert\Regex(
     *     pattern="/^(\s)+/",
     *     match=false,
     *     message="Il ne peut y avoir d'espaces dans votre nom"
     * )
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity=Enterprise::class, inversedBy="users")
     */
    private $enterprise;

    /**
     * @ORM\OneToOne(targetEntity=Advisor::class, inversedBy="user", cascade={"persist", "remove"})
     */
    private $advisor;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     * @Assert\Regex(
     *     pattern="/H|F/",
     *     match=true,
     *     message="Le genre ne peut être que H ou F."
     * )
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Regex(
     *     pattern="/[1-9]{1,}/",
     *     match=false,
     *     message="La nationalité ne peut contenir que des lettres."
     * )
     */
    private $nationality;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(
     * max = 10,
     * maxMessage = "Le code postal  ne peut dépasser {{ limit }} caractéres",
     * allowEmptyString = true
     * )
     */
    private $postCode;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $pictureLink;

    /**
     * @ORM\Column(type="string", nullable=true, length=50)
     * @Assert\NotBlank(message="Merci de sélectionner un type")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
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
        $roles[] = 'ROLE_USER';

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
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(?string $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

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

    public function getPictureLink(): ?string
    {
        return $this->pictureLink;
    }

    public function setPictureLink(?string $pictureLink): self
    {
        $this->pictureLink = $pictureLink;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }
}
