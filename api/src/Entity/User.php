<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\EntityListener\UserEntityListener;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use JsonSerializable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
            "method" => "DELETE",
        ]
    ],
)]
#[ApiFilter(SearchFilter::class, properties: [
    "email"       => "exact",
    "firstName"   => "partial",
    "lastName"    => "exact",
])]
#[ORM\EntityListeners([UserEntityListener::class])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, JsonSerializable
{

    public const ROLE_USER  = "ROLE_USER";
    public const ROLE_ADMIN   = "ROLE_ADMIN";
    public const ROLE_MANAGER = "ROLE_MANAGER";

    /**
     * @var string|null
     */
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    private ?string $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 180, unique: true)]
    #[Groups([
        "get:collection:user",
        "get:item:user",
        "post:collection:user",
        "put:item:user"
    ])]
    private ?string $email = null;

    /**
     * @var string[]
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string|null
     */
    #[ORM\Column]
    #[Groups([
        "post:collection:user",
        "put:item:user"
    ])]
    private ?string $password = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "post:collection:user",
        "put:item:user",
        "get:collection:user",
        "get:item:user",
    ])]
    private ?string $firstName = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "post:collection:user",
        "put:item:user",
        "get:collection:user",
        "get:item:user",
    ])]
    private ?string $lastName = null;

    /**
     * @var bool|null
     */
    #[ORM\Column]
    private ?bool $isConfirmed = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $registrationToken = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[OneToMany(mappedBy: 'user', targetEntity: CourseUser::class)]
    private Collection $courseUsers;

    /**
     * @var Collection|ArrayCollection
     */
    #[OneToMany(mappedBy: 'user', targetEntity: TaskUser::class)]
    private Collection $taskUsers;

    /**
     * @var Collection|ArrayCollection
     */
    #[OneToMany(mappedBy: 'user', targetEntity: Comment::class)]
    private Collection $comments;

    /**
     * @var Collection|ArrayCollection
     */
    #[OneToMany(mappedBy: 'user', targetEntity: Certificate::class)]
    private Collection $certificates;

    /**
     * User constructor
     */
    public function __construct()
    {
        $uuid = UuidV6::v6();
        $this->id = $uuid->toRfc4122();
        $this->isConfirmed = false;
        $this->roles = [self::ROLE_USER];
        $this->courseUsers = new ArrayCollection();
        $this->taskUsers = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->certificates = new ArrayCollection();
    }

    /**
     * @return ?string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function getCertificates(): Collection
    {
        return $this->certificates;
    }

    /**
     * @param Collection $certificates
     * @return $this
     */
    public function setCertificates(Collection $certificates): self
    {
        $this->certificates = $certificates;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return void
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    /**
     * @param bool $isConfirmed
     * @return $this
     */
    public function setIsConfirmed(bool $isConfirmed): self
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegistrationToken(): ?string
    {
        return $this->registrationToken;
    }

    /**
     * @param string|null $registrationToken
     * @return $this
     */
    public function setRegistrationToken(?string $registrationToken): self
    {
        $this->registrationToken = $registrationToken;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCourseUsers(): Collection
    {
        return $this->courseUsers;
    }

    /**
     * @param Collection $courseUsers
     * @return $this
     */
    public function setCourseUsers(Collection $courseUsers): self
    {
        $this->courseUsers = $courseUsers;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTaskUsers(): Collection
    {
        return $this->taskUsers;
    }

    /**
     * @param Collection $taskUsers
     * @return $this
     */
    public function setTaskUsers(Collection $taskUsers): self
    {
        $this->taskUsers = $taskUsers;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Collection $comments
     * @return $this
     */
    public function setComments(Collection $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id"          => $this->getId(),
            "email"       => $this->getEmail(),
            "firstName"   => $this->getFirstName(),
            "lastName"    => $this->getLastName(),
            "isConfirmed" => $this->getIsConfirmed()
        ];
    }
}
