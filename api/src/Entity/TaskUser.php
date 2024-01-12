<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TaskUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6;

#[ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:taskUser"]],
        ],
        "post" => [
            "method"                  => "POST",
            "denormalization_context" => ["groups" => ["post:collection:taskUser"]],
            "normalization_context"   => ["groups" => ["get:item:taskUser"]]
        ]
    ],
    itemOperations: [
        "get"    => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:taskUser"]],
        ],
        "put"    => [
            "method"                  => "PUT",
            "denormalization_context" => ["groups" => ["put:item:taskUser"]],
            "normalization_context"   => ["groups" => ["get:item:taskUser"]],
        ],
        "delete" => [
            "method"   => "DELETE",
        ]
    ],
)]
#[ORM\Entity(repositoryClass: TaskUserRepository::class)]
class TaskUser
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
    private ?string $taskId = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $userId = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $solvedTaskFileName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $descriptionSolvedTask = null;

    #[ORM\Column]
    #[Groups([
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?bool $isDone = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Groups([
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $mark = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "taskUsers")]
    #[Groups([
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: "taskUsers")]
    #[Groups([
        "put:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?Task $task = null;

    public function __construct()
    {
        $uuid = UuidV6::v6();
        $this->id = $uuid->toRfc4122();
        $this->isDone = false;
        $this->mark = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskId(): ?string
    {
        return $this->taskId;
    }

    public function setTaskId(string $taskId): static
    {
        $this->taskId = $taskId;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getSolvedTaskFileName(): ?string
    {
        return $this->solvedTaskFileName;
    }

    public function setSolvedTaskFileName(?string $solvedTaskFileName): static
    {
        $this->solvedTaskFileName = $solvedTaskFileName;

        return $this;
    }

    public function getDescriptionSolvedTask(): ?string
    {
        return $this->descriptionSolvedTask;
    }

    public function setDescriptionSolvedTask(?string $descriptionSolvedTask): static
    {
        $this->descriptionSolvedTask = $descriptionSolvedTask;

        return $this;
    }

    public function isIsDone(): ?bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): static
    {
        $this->isDone = $isDone;

        return $this;
    }

    public function getMark(): ?string
    {
        return $this->mark;
    }

    public function setMark(string $mark): static
    {
        $this->mark = $mark;

        return $this;
    }
}
