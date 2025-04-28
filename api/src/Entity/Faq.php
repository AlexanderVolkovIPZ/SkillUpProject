<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\FaqRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6;

#[ApiResource(
    collectionOperations: [
        "get"  => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:faq"]],
        ],
        "post" => [
            "method"                  => "POST",
            "denormalization_context" => ["groups" => ["post:collection:faq"]],
            "normalization_context"   => ["groups" => ["get:item:faq"]],
        ]
    ],
    itemOperations: [
        "get"    => [
            "method"                => "GET",
            "normalization_context" => ["groups" => ["get:collection:faq"]],
        ],
        "put"    => [
            "method"                  => "PUT",
            "denormalization_context" => ["groups" => ["put:item:faq"]],
            "normalization_context"   => ["groups" => ["get:item:faq"]],
        ],
        "delete" => [
            "method" => "DELETE",
        ]
    ],
)]
#[ApiFilter(SearchFilter::class, properties: [
    "question" => "partial",
    "answer"   => "partial",
])]
#[ApiFilter(RangeFilter::class, properties: [
    "createdAt",
])]
#[ORM\Entity(repositoryClass: FaqRepository::class)]
class Faq
{
    /**
     * @var int|string|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:collection:faq",
        "get:item:faq",
    ])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "post:collection:faq",
        "put:item:faq",
        "get:collection:faq",
        "get:item:faq",
    ])]
    private ?string $question = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "post:collection:faq",
        "put:item:faq",
        "get:collection:faq",
        "get:item:faq",
    ])]
    private ?string $answer = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([
        "put:item:faq",
        "get:collection:faq",
        "get:item:faq",
    ])]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var Course|null
     */
    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: "faqs")]
    #[Groups([
        "post:collection:faq",
        "put:item:faq",
        "get:collection:faq",
        "get:item:faq",
    ])]
    private ?Course $course = null;

    /**
     * Faq constructor
     */
    public function __construct()
    {
        $this->id = UuidV6::v6()->toRfc4122();
        $this->createdAt = new \DateTime('now');
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
    public function getQuestion(): ?string
    {
        return $this->question;
    }

    /**
     * @param string $question
     * @return $this
     */
    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     * @return $this
     */
    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

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
}
