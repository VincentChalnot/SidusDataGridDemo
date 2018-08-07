<?php

namespace AppBundle\Entity;

use AppBundle\Utils\StringUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="news")
 * @ORM\Entity()
 *
 * @UniqueEntity(fields={"slug"})
 */
class News
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $publicationDate;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Choice({
     *     "draft",
     *     "rejected",
     *     "validated",
     *     "published",
     *     "unpublished",
     * })
     *
     * @ORM\Column(type="string")
     */
    protected $publicationStatus;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Author", cascade={"detach"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $author;

    /**
     * @var Category[]|Collection|iterable
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinTable(name="news_category")
     */
    protected $categories;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $deleted = false;

    /**
     * Setting initial dates
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->categories = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
        if (null === $this->slug) {
            $this->slug = StringUtils::slugify($title);
        }
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getPublicationDate(): \DateTime
    {
        return $this->publicationDate;
    }

    /**
     * @param \DateTime $publicationDate
     */
    public function setPublicationDate(\DateTime $publicationDate): void
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     * @return string
     */
    public function getPublicationStatus(): string
    {
        return $this->publicationStatus;
    }

    /**
     * @param string $publicationStatus
     */
    public function setPublicationStatus(string $publicationStatus): void
    {
        $this->publicationStatus = $publicationStatus;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Author
     */
    public function getAuthor(): Author
    {
        return $this->author;
    }

    /**
     * @param Author $author
     */
    public function setAuthor(Author $author): void
    {
        $this->author = $author;
    }

    /**
     * @return Category[]|Collection|iterable
     */
    public function getCategories(): iterable
    {
        return $this->categories;
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category): void
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }
    }

    /**
     * @param Category $category
     */
    public function removeCategory(Category $category): void
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
