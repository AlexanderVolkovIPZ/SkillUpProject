<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\EntityListener\CourseEntityListener;
use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use JsonSerializable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6;

#[ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:course"]],
        ],
        "post" => [
            "method"                  => "POST",
            "denormalization_context" => ["groups" => ["post:collection:course"]],
            "normalization_context"   => ["groups" => ["get:item:course"]]
        ]
    ],
    itemOperations: [
        "get"    => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:course"]],
        ],
        "put"    => [
            "method"                  => "PUT",
            "denormalization_context" => ["groups" => ["put:item:course"]],
            "normalization_context"   => ["groups" => ["get:item:course"]],
        ],
        "delete" => [
            "method" => "DELETE",
        ]
    ],
)]
#[ORM\EntityListeners([CourseEntityListener::class])]
#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course implements JsonSerializable
{

    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    #[Groups([
        "post:collection:course",
        "put:item:course",
        "get:collection:course",
        "get:item:course",
    ])]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        "post:collection:course",
        "put:item:course",
        "get:collection:course",
        "get:item:course",
    ])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "post:collection:course",
        "put:item:course",
        "get:collection:course",
        "get:item:course",
    ])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([
        "post:collection:course",
        "put:item:course",
        "get:collection:course",
        "get:item:course",
    ])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        "put:item:course",
        "get:collection:course",
        "get:item:course",
    ])]
    private ?string $code = null;

    #[OneToMany(mappedBy: 'course', targetEntity: CourseUser::class)]
    private Collection $courseUsers;

    #[OneToMany(mappedBy: 'course', targetEntity: Task::class)]
    private Collection $tasks;

    public function __construct()
    {
        $uuid = UuidV6::v6();
        $this->id = $uuid->toRfc4122();
        $this->courseUsers = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCourseUsers(): Collection
    {
        return $this->courseUsers;
    }

    public function setCourseUsers(Collection $courseUsers): void
    {
        $this->courseUsers = $courseUsers;
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function setTasks(Collection $tasks): void
    {
        $this->tasks = $tasks;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            "id"          => $this->getId(),
            "name"        => $this->getName(),
            "title"       => $this->getTitle(),
            "description" => $this->getDescription(),
            "code"        => $this->getCode(),
        ];
    }

}
