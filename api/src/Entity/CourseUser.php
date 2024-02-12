<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CourseUserRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:courseUser"]],
        ],
        "post" => [
            "method"                  => "POST",
            "denormalization_context" => ["groups" => ["post:collection:courseUser"]],
            "normalization_context"   => ["groups" => ["get:item:courseUser"]]
        ]
    ],
    itemOperations: [
        "get"    => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:courseUser"]],
        ],
        "put"    => [
            "method"                  => "PUT",
            "denormalization_context" => ["groups" => ["put:item:courseUser"]],
            "normalization_context"   => ["groups" => ["get:item:courseUser"]],
        ],
        "delete" => [
            "method" => "DELETE",
        ]
    ],
)]
#[UniqueEntity(
    fields: [
        'user',
        'course'
    ],
    message: "This combination is already used."
)]
#[ORM\Entity(repositoryClass: CourseUserRepository::class)]
class CourseUser implements JsonSerializable
{

    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    #[Groups([
        "get:collection:courseUser",
        "get:item:courseUser",
    ])]
    private ?string $id = null;

    #[ORM\Column]
    #[Groups([
        "post:collection:courseUser",
        "put:item:courseUser",
        "get:collection:courseUser",
        "get:item:courseUser",
    ])]
    private ?bool $isCreator = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "courseUsers")]
    #[Groups([
        "post:collection:courseUser",
        "put:item:courseUser",
        "get:collection:courseUser",
        "get:item:courseUser",
    ])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: "courseUsers")]
    #[Groups([
        "post:collection:courseUser",
        "put:item:courseUser",
        "get:collection:courseUser",
        "get:item:courseUser",
    ])]
    private ?Course $course = null;

    public function __construct()
    {
        $uuid = UuidV6::v6();
        $this->id = $uuid->toRfc4122();
        $this->isCreator = false;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getIsCreator(): ?bool
    {
        return $this->isCreator;
    }

    public function setIsCreator(bool $isCreator): self
    {
        $this->isCreator = $isCreator;

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

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            "id"        => $this->getId(),
            "isCreator" => $this->getIsCreator(),
        ];
    }

}
