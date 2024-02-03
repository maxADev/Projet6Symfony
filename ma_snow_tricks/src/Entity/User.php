<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\String\ByteString;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Util\DateInterface;
use App\Model\DateTrait;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, DateInterface
{
    use DateTrait;

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('name', new NotBlank());
        $metadata->addPropertyConstraint('email', new NotBlank());
        $metadata->addPropertyConstraint('password', new NotBlank());
        $metadata->addPropertyConstraint('confirmPassword', new NotBlank());

        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'name',
        ]));

        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'email',
        ]));

        $metadata->addPropertyConstraint('confirmPassword', new EqualTo([
            'propertyPath' => 'password',
            'message' => 'Les mots de passes sont différents',
        ]));

        $metadata->addPropertyConstraint('email',  new Email([
            'message' => 'Le format de l\'email n\'est pas valide',
        ]));

        $metadata->addPropertyConstraint('password', new Length([
            'min' => 5,
            'max' => 60,
            'minMessage' => 'Le mot de passe doit faire 5 caractères minimum',
            'maxMessage' => 'Le mot de passe peut faire 60 caractères maximum',
        ]));
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $email = null;

    #[ORM\Column(length: 60)]
    private ?string $password = null;

    private ?string $confirmPassword = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $registrationToken = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registrationTokenDate = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $resetPasswordToken = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $resetPasswordTokenDate = null;

    #[ORM\Column]
    private ?bool $statut = null;

    private $cgu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $cguDate = null;

    private ?bool $roles = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): static
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(): static
    {
        $this->creationDate = $this->createDateTime();

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(): static
    {
        $this->modificationDate = $this->createDateTime();

        return $this;
    }

    public function getRegistrationToken(): ?string
    {
        return $this->registrationToken;
    }

    public function setRegistrationToken(?string $registrationToken): static
    {
        $this->registrationToken = $registrationToken;

        return $this;
    }

    public function getRegistrationTokenDate(): ?\DateTimeInterface
    {
        return $this->registrationTokenDate;
    }

    public function setRegistrationTokenDate(): static
    {
        $this->registrationTokenDate = $this->createDateTime();

        return $this;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $resetPasswordToken): static
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    public function getResetPasswordTokenDate(): ?\DateTimeInterface
    {
        return $this->resetPasswordTokenDate;
    }

    public function setResetPasswordTokenDate(): static
    {
        $this->resetPasswordTokenDate = $this->createDateTime();

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCgu(): ?string
    {
        return $this->cgu;
    }

    public function setCgu(bool $cgu): static
    {
        $this->cgu = $cgu;

        return $this;
    }

    public function getCguDate(): ?\DateTimeInterface
    {
        return $this->cguDate;
    }

    public function setCguDate(): static
    {
        $this->cguDate = $this->createDateTime();

        return $this;
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
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Returning a salt is only needed if you are not using a modern
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
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function generateRegistrationToken(): void
    {
        $this->registrationToken = ByteString::fromRandom(60);
    }

    public function removeRegistrationToken(): void
    {
        $this->registrationToken = null;
    }

    public function removeRegistrationTokenDate(): void
    {
        $this->registrationTokenDate = null;
    }

    public function generateResetPasswordToken(): void
    {
        $this->resetPasswordToken = ByteString::fromRandom(60);
    }

    public function removeResetPasswordToken(): void
    {
        $this->resetPasswordToken = null;
    }

    public function removeResetPasswordTokenDate(): void
    {
        $this->resetPasswordTokenDate = null;
    }

    public function hashPassword(): void
    {
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $this->password = $hashedPassword;
    }
}
