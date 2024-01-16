<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CourseUserRepository;
use Doctrine\ORM\Mapping as ORM;
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
class CourseUser
{

    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
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

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): static
    {
        $this->userId = $userId;

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

    public function isIsCreator(): ?bool
    {
        return $this->isCreator;
    }

    public function setIsCreator(bool $isCreator): static
    {
        $this->isCreator = $isCreator;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): void
    {
        $this->course = $course;
    }

}
