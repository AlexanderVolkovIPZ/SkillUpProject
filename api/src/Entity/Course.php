<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
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
            'method'   => 'DELETE',
        ]
    ],
)]
#[ApiFilter(SearchFilter::class, properties: [
    "name"         => "partial",
    "title"        => "partial",
    "description"  => "partial",
    "code"         => 'exact'
])]
#[ApiFilter(RangeFilter::class, properties: [
    'createdAt',
])]
#[ORM\EntityListeners([CourseEntityListener::class])]
#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course implements JsonSerializable
{
    /**
     * @var string|null
     */
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    #[Groups([
        "post:collection:course",
        "put:item:course",
        "get:collection:course",
        "get:item:course",
    ])]
    private ?string $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "post:collection:course",
        "put:item:course",
        "get:collection:course",
        "get:item:course",
    ])]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "post:collection:course",
        "put:item:course",
        "get:collection:course",
        "get:item:course",
    ])]
    private ?string $title = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([
        "post:collection:course",
        "put:item:course",
        "get:collection:course",
        "get:item:course",
    ])]
    private ?string $description = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "put:item:course",
        "get:collection:course",
        "get:item:course",
    ])]
    private ?string $code = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[OneToMany(mappedBy: 'course', targetEntity: CourseUser::class)]
    private Collection $courseUsers;

    /**
     * @var Collection|ArrayCollection
     */
    #[OneToMany(mappedBy: 'course', targetEntity: Task::class)]
    private Collection $tasks;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: 'course', targetEntity: CourseCategory::class, cascade: ['persist', 'remove'])]
    private Collection $courseCategories;

    /**
     * @var Collection
     */
    #[OneToMany(mappedBy: 'course', targetEntity: Comment::class)]
    private Collection $comments;

    /**
     * @var Collection
     */
    #[OneToMany(mappedBy: 'course', targetEntity: Certificate::class)]
    private Collection $certificates;


    /**
     * @var Collection
     */
    #[OneToMany(mappedBy: 'course', targetEntity: Faq::class)]
    private Collection $faqs;


    /**
     * Course constructor
     */
    public function __construct()
    {
        $this->id = UuidV6::v6()->toRfc4122();
        $this->courseUsers = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->courseCategories = new ArrayCollection();
        $this->certificates = new ArrayCollection();
        $this->faqs = new ArrayCollection();
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return $this
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

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
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * @param Collection $tasks
     * @return $this
     */
    public function setTasks(Collection $tasks): self
    {
        $this->tasks = $tasks;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCourseCategories(): Collection
    {
        return $this->courseCategories;
    }

    /**
     * @param Collection $courseCategories
     * @return self
     */
    public function setCourseCategories(Collection $courseCategories): self
    {
        $this->courseCategories = $courseCategories;

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
            "name"        => $this->getName(),
            "title"       => $this->getTitle(),
            "description" => $this->getDescription(),
            "code"        => $this->getCode(),
        ];
    }
}
