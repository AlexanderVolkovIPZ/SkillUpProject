<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\EntityListener\TaskUserEntityListener;
use App\Repository\TaskUserRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
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
        "patch"  => [
            "method"                  => "PATCH",
            "denormalization_context" => ["groups" => ["patch:item:taskUser"]],
            "normalization_context"   => ["groups" => ["get:item:taskUser"]],
        ],
        "delete" => [
            "method" => "DELETE",
        ]
    ],
)]
#[ORM\EntityListeners([TaskUserEntityListener::class])]
#[ORM\Entity(repositoryClass: TaskUserRepository::class)]
class TaskUser implements JsonSerializable
{

    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    private ?string $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "patch:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $solvedTaskFileName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "patch:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $linkSolvedTask = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    #[Groups([
        "put:item:taskUser",
        "patch:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?string $mark = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "taskUsers")]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "patch:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: "taskUsers")]
    #[Groups([
        "post:collection:taskUser",
        "put:item:taskUser",
        "patch:item:taskUser",
        "get:collection:taskUser",
        "get:item:taskUser",
    ])]
    private ?Task $task = null;

    public function __construct()
    {
        $uuid = UuidV6::v6();
        $this->id = $uuid->toRfc4122();
        $this->mark = null;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSolvedTaskFileName(): ?string
    {
        return $this->solvedTaskFileName;
    }

    public function setSolvedTaskFileName(?string $solvedTaskFileName): self
    {
        $this->solvedTaskFileName = $solvedTaskFileName;

        return $this;
    }

    public function getLinkSolvedTask(): ?string
    {
        return $this->linkSolvedTask;
    }

    public function setLinkSolvedTask(?string $linkSolvedTask): self
    {
        $this->linkSolvedTask = $linkSolvedTask;

        return $this;
    }

    public function getMark(): ?string
    {
        return $this->mark;
    }

    public function setMark(string $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }


    public function jsonSerialize(): array
    {
        return [
            "id"                 => $this->getId(),
            "solvedTaskFileName" => $this->getSolvedTaskFileName(),
            "linkSolvedTask"     => $this->getLinkSolvedTask(),
            "mark"               => $this->getMark()
        ];
    }

}
