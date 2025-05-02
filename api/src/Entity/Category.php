<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:category"]],
        ],
        "post" => [
            "method"                  => "POST",
            "denormalization_context" => ["groups" => ["post:collection:category"]],
            "normalization_context"   => ["groups" => ["get:item:category"]],
        ]
    ],
    itemOperations: [
        "get"    => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:category"]],
        ],
        "put"    => [
            "method"                  => "PUT",
            "denormalization_context" => ["groups" => ["put:item:category"]],
            "normalization_context"   => ["groups" => ["get:item:category"]],
        ],
        "delete" => [
            "method" => "DELETE",
            "security" => "is_granted('" . User::ROLE_ADMIN . "') or is_granted('" . User::ROLE_MANAGER . "')"
        ]
    ],
)]
#[ApiFilter(SearchFilter::class, properties: [
    "name"  => "partial",
])]
#[ApiFilter(RangeFilter::class, properties: [
    'createdAt',
])]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    /**
     * @var string|null
     */
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    #[Groups([
        "get:collection:category",
        "get:item:category",
    ])]
    private ?string $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:collection:category",
        "get:item:category",
        "post:collection:category",
        "put:item:category"
    ])]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "get:collection:category",
        "get:item:category",
        "post:collection:category",
        "put:item:category"
    ])]
    private ?string $description = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups([
        "get:collection:category",
        "get:item:category",
        "put:item:category"
    ])]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: CourseCategory::class, cascade: ['persist', 'remove'])]
    private Collection $courseCategories;

    /**
     * Category constructor
     */
    public function __construct()
    {
        $this->id = UuidV6::v6()->toRfc4122();
        $this->courseCategories = new ArrayCollection();
        $this->createdAt = new \DateTime('now');
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
     * @return Collection
     */
    public function getCourseCategories(): Collection
    {
        return $this->courseCategories;
    }

    /**
     * @param Collection $courseCategories
     * @return $this
     */
    public function setCourseCategories(Collection $courseCategories): self
    {
        $this->courseCategories = $courseCategories;

        return $this;
    }
}
