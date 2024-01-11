<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\EntityListener\UserEntityListener;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\UuidV6;

#[ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:user"]],
        ],
        "post" => [
            "method"                  => "POST",
            "denormalization_context" => ["groups" => ["post:collection:user"]],
            "normalization_context"   => ["groups" => ["get:item:user"]]
        ]
    ],
    itemOperations: [
        "get"    => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:user"]],
        ],
        "put"    => [
            "method"                  => "PUT",
            "denormalization_context" => ["groups" => ["put:item:user"]],
            "normalization_context"   => ["groups" => ["get:item:user"]],
        ],
        "delete" => [
            "method"   => "DELETE",
        ]
    ],
)]
#[ORM\EntityListeners([UserEntityListener::class])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const ROLE_USER  = "ROLE_USER";
    const ROLE_ADMIN = "ROLE_ADMIN";

    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    private ?string $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups([
        "get:collection:user",
        "get:item:user",
        "post:collection:user",
        "put:item:user"
    ])]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups([
        "post:collection:user",
        "put:item:user"
    ])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        "post:collection:user",
        "put:item:user",
        "get:collection:user",
        "get:item:user",
        "put:item:user"
    ])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        "post:collection:user",
        "put:item:user",
        "get:collection:user",
        "get:item:user",
        "put:item:user"
    ])]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?bool $isConfirmed = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $registrationToken = null;

    public function __construct()
    {
        $uuid = UuidV6::v6();
        $this->id = $uuid->toRfc4122();
        $this->isConfirmed = false;
        $this->roles = [self::ROLE_USER];
    }

    public function getId(): ?string
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

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getIsConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): self
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    public function getRegistrationToken(): ?string
    {
        return $this->registrationToken;
    }

    public function setRegistrationToken(?string $registrationToken): self
    {
        $this->registrationToken = $registrationToken;

        return $this;
    }

}
