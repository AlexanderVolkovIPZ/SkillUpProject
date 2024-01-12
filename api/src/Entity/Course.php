<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6;

#[ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:task"]],
        ],
        "post" => [
            "method"                  => "POST",
            "denormalization_context" => ["groups" => ["post:collection:task"]],
            "normalization_context"   => ["groups" => ["get:item:task"]]
        ]
    ],
    itemOperations: [
        "get"    => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:task"]],
        ],
        "put"    => [
            "method"                  => "PUT",
            "denormalization_context" => ["groups" => ["put:item:task"]],
            "normalization_context"   => ["groups" => ["get:item:task"]],
        ],
        "delete" => [
            "method"   => "DELETE",
        ]
    ],
)]
#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $description = null;

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

    public function getId(): ?int
    {
        return $this->id;
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

}
