<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TaskRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use JsonSerializable;
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
            "method" => "DELETE",
        ]
    ],
)]
#[ApiFilter(SearchFilter::class, properties: [
    "name"         => "partial",
    "description"  => "partial",
    "fileNameTask" => "partial",
])]
#[ApiFilter(RangeFilter::class, properties: [
    "createdAt",
    "maxMark",
    "dueDate",
])]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task implements JsonSerializable
{
    /**
     * @var string|null
     */
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    #[Groups([
        "get:collection:task",
        "get:item:task",
    ])]
    private ?string $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "post:collection:task",
        "put:item:task",
        "get:collection:task",
        "get:item:task",
    ])]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([
        "post:collection:task",
        "put:item:task",
        "get:collection:task",
        "get:item:task",
    ])]
    private ?string $description = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "post:collection:task",
        "put:item:task",
        "get:collection:task",
        "get:item:task",
    ])]
    private ?string $fileNameTask = null;

    /**
     * @var string|int|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Groups([
        "post:collection:task",
        "put:item:task",
        "get:collection:task",
        "get:item:task",
    ])]
    private ?string $maxMark = null;

    /**
     * @var DateTimeInterface|DateTime|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([
        "get:collection:task",
        "get:item:task",
    ])]
    private ?DateTimeInterface $createdAt = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups([
        "post:collection:task",
        "put:item:task",
        "get:collection:task",
        "get:item:task",
    ])]
    private ?DateTimeInterface $dueDate = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[OneToMany(mappedBy: 'task', targetEntity: TaskUser::class)]
    private Collection $taskUsers;

    /**
     * @var Course|null
     */
    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: "tasks")]
    #[Groups([
        "post:collection:task",
        "put:item:task",
        "get:collection:task",
        "get:item:task",
    ])]
    private ?Course $course = null;

    /**
     * Task constructor
     */
    public function __construct()
    {
        $uuid = UuidV6::v6();
        $this->id = $uuid->toRfc4122();
        $this->createdAt = new DateTime(datetime: "now");
        $this->taskUsers = new ArrayCollection();
        $this->maxMark = 0;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return $this
     */
    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFileNameTask(): ?string
    {
        return $this->fileNameTask;
    }

    /**
     * @param string|null $fileNameTask
     * @return $this
     */
    public function setFileNameTask(?string $fileNameTask): self
    {
        $this->fileNameTask = $fileNameTask;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDueDate(): ?DateTimeInterface
    {
        return $this->dueDate;
    }

    /**
     * @param DateTimeInterface|null $dueDate
     * @return $this
     */
    public function setDueDate(?DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;

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
     * @return string|null
     */
    public function getMaxMark(): ?string
    {
        return $this->maxMark;
    }

    /**
     * @param string $maxMark
     * @return $this
     */
    public function setMaxMark(string $maxMark): self
    {
        $this->maxMark = $maxMark;

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

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id"           => $this->getId(),
            "name"         => $this->getName(),
            "description"  => $this->getDescription(),
            "fileNameTask" => $this->getFileNameTask(),
            "maxMark"      => $this->getMaxMark(),
            "createdAt"    => $this->getCreatedAt(),
            "dueDate"      => $this->getDueDate()
        ];
    }
}
