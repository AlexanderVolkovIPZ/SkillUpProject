<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use App\Repository\CourseCategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6;

#[ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:courseCategory"]],
        ],
        "post" => [
            "method"                  => "POST",
            "denormalization_context" => ["groups" => ["post:collection:courseCategory"]],
            "normalization_context"   => ["groups" => ["get:item:courseCategory"]],
        ]
    ],
    itemOperations: [
        "get"    => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:courseCategory"]],
        ],
        "put"    => [
            "method"                  => "PUT",
            "denormalization_context" => ["groups" => ["put:item:courseCategory"]],
            "normalization_context"   => ["groups" => ["get:item:courseCategory"]],
        ],
        "delete" => [
            "method" => "DELETE",
        ]
    ],
)]
#[ApiFilter(RangeFilter::class, properties: [
    'createdAt',
])]
#[ORM\Entity(repositoryClass: CourseCategoryRepository::class)]
class CourseCategory
{
    /**
     * @var string|null
     */
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    #[Groups([
        "get:collection:courseCategory",
        "get:item:courseCategory",
    ])]
    private ?string $id = null;

    /**
     * @var Course|null
     */
    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'courseCategories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        "post:collection:courseCategory",
        "put:item:courseCategory",
        "get:collection:courseCategory",
        "get:item:courseCategory",
    ])]
    private ?Course $course = null;

    /**
     * @var Category|null
     */
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'courseCategories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        "post:collection:courseCategory",
        "put:item:courseCategory",
        "get:collection:courseCategory",
        "get:item:courseCategory",
    ])]
    private ?Category $category = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups([
        "post:collection:courseCategory",
        "put:item:courseCategory",
        "get:collection:courseCategory",
        "get:item:courseCategory",
    ])]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * Category constructor
     */
    public function __construct()
    {
        $this->id = UuidV6::v6()->toRfc4122();
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
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
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
}
