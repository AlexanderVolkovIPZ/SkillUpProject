<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CertificateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6;
use DateTime;

/**
 *
 */
#[ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:certificate"]],
        ],
        "post" => [
            "method"                  => "POST",
            "denormalization_context" => ["groups" => ["post:collection:certificate"]],
            "normalization_context"   => ["groups" => ["get:item:certificate"]],
        ]
    ],
    itemOperations: [
        "get"    => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:certificate"]],
        ],
        "put"    => [
            "method"                  => "PUT",
            "denormalization_context" => ["groups" => ["put:item:certificate"]],
            "normalization_context"   => ["groups" => ["get:item:certificate"]],
        ],
        "delete" => [
            "method" => "DELETE",
        ]
    ],
)]
#[ApiFilter(SearchFilter::class, properties: [
    "certificateUrl" => "partial",
])]
#[ApiFilter(RangeFilter::class, properties: [
    'generatedAt',
])]
#[ORM\Entity(repositoryClass: CertificateRepository::class)]
class Certificate
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:collection:certificate",
        "get:item:certificate",
    ])]
    private ?int $id = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([
        "put:item:certificate",
        "get:collection:certificate",
        "get:item:certificate",
    ])]
    private ?\DateTimeInterface $generatedAt = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "post:collection:certificate",
        "put:item:certificate",
        "get:collection:certificate",
        "get:item:certificate",
    ])]
    private ?string $certificateUrl = null;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "certificates")]
    #[Groups([
        "post:collection:certificate",
        "put:item:certificate",
        "get:collection:certificate",
        "get:item:certificate",
    ])]
    private ?User $user = null;

    /**
     * @var Course|null
     */
    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: "certificates")]
    #[Groups([
        "post:collection:certificate",
        "put:item:certificate",
        "get:collection:certificate",
        "get:item:certificate",
    ])]
    private ?Course $course = null;

    /**
     * Certificate constructor
     */
    public function __construct()
    {
        $this->id = UuidV6::v6()->toRfc4122();
        $this->generatedAt = new DateTime(datetime: "now");
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return \DateTimeInterface|null
     */
    public function getGeneratedAt(): ?\DateTimeInterface
    {
        return $this->generatedAt;
    }

    /**
     * @param \DateTimeInterface $generatedAt
     * @return $this
     */
    public function setGeneratedAt(\DateTimeInterface $generatedAt): self
    {
        $this->generatedAt = $generatedAt;

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
     * @return string|null
     */
    public function getCertificateUrl(): ?string
    {
        return $this->certificateUrl;
    }

    /**
     * @param string $certificateUrl
     * @return $this
     */
    public function setCertificateUrl(string $certificateUrl): self
    {
        $this->certificateUrl = $certificateUrl;

        return $this;
    }
}
