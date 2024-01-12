<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6;
use ApiPlatform\Core\Annotation\ApiResource;

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
#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
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

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $fileNameTask = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups([
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?DateTimeInterface $dueDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $maxMark = null;

    #[OneToMany(mappedBy: 'task', targetEntity: TaskUser::class)]
    private Collection $taskUsers;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: "tasks")]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?Course $course = null;

    public function __construct()
    {
        $uuid = UuidV6::v6();
        $this->id = $uuid->toRfc4122();
        $this->createdAt = new DateTime(datetime: "now");
        $this->taskUsers = new ArrayCollection();
        $this->maxMark = 0;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFileNameTask(): ?string
    {
        return $this->fileNameTask;
    }

    public function setFileNameTask(?string $fileNameTask): static
    {
        $this->fileNameTask = $fileNameTask;

        return $this;
    }

    public function getCourseId(): ?string
    {
        return $this->courseId;
    }

    public function setCourseId(string $courseId): static
    {
        $this->courseId = $courseId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getTaskUsers(): Collection
    {
        return $this->taskUsers;
    }

    public function setTaskUsers(Collection $taskUsers): void
    {
        $this->taskUsers = $taskUsers;
    }

    public function getMaxMark(): ?string
    {
        return $this->maxMark;
    }

    public function setMaxMark(string $maxMark): static
    {
        $this->maxMark = $maxMark;

        return $this;
    }

}
