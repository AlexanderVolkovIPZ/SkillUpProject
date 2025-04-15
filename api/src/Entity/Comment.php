<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6;

#[ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:comment"]],
        ],
        "post" => [
            "method"                  => "POST",
            "denormalization_context" => ["groups" => ["post:collection:comment"]],
            "normalization_context"   => ["groups" => ["get:item:comment"]],
        ]
    ],
    itemOperations: [
        "get"    => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:comment"]],
        ],
        "put"    => [
            "method"                  => "PUT",
            "denormalization_context" => ["groups" => ["put:item:comment"]],
            "normalization_context"   => ["groups" => ["get:item:comment"]],
        ],
        "delete" => [
            'method'   => 'DELETE',
            "security" => "is_granted('" . User::ROLE_ADMIN . "') or is_granted('" . User::ROLE_MANAGER . "') or object.getUser() == user"
        ]
    ],
)]
#[ApiFilter(RangeFilter::class, properties: [
    'createdAt',
])]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    /**
     * @var string|null
     */
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    #[Groups([
        "get:collection:comment",
        "get:item:comment",
    ])]
    private ?string $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:collection:comment",
        "get:item:comment",
        "post:collection:comment",
        "put:item:comment"
    ])]
    private ?string $userId = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:collection:comment",
        "get:item:comment",
        "post:collection:comment",
        "put:item:comment"
    ])]
    private ?string $courseId = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups([
        "get:collection:comment",
        "get:item:comment",
        "post:collection:comment",
        "put:item:comment"
    ])]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "comments")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[Groups([
        "get:collection:comment",
        "get:item:comment",
        "post:collection:comment",
        "put:item:comment"
    ])]
    private ?User $user= null;

    /**
     * @var Course|null
     */
    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: "comments")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[Groups([
        "get:collection:comment",
        "get:item:comment",
        "post:collection:comment",
        "put:item:comment"
    ])]
    private ?Course $course= null;

    /**
     * Category constructor
     */
    public function __construct()
    {
        $this->id = UuidV6::v6()->toRfc4122();
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return $this
     */
    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCourseId(): ?string
    {
        return $this->courseId;
    }

    /**
     * @param string $courseId
     * @return $this
     */
    public function setCourseId(string $courseId): self
    {
        $this->courseId = $courseId;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Course|null
     */
    public function getCourse(): ?Course
    {
        return $this->course;
    }

    /**
     * @param Course|null $course
     * @return $this
     */
    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }
}
