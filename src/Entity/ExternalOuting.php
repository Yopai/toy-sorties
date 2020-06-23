<?php

namespace App\Entity;

use App\Repository\ExternalOutingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExternalOutingRepository::class)
 */
class ExternalOuting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalSource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $source;

    /**
     * @ORM\Column(type="integer")
     */
    private $externalId;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $startDate;

    /**
     * @ORM\Column(type="time")
     */
    private $startTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $maxRegistrations;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $currentRegistrations;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=OutingCategory::class)
     */
    private $category;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $waitingRegistrations;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $virt;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $department;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $externalUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?ExternalSource
    {
        return $this->source;
    }

    public function setSource(?ExternalSource $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    public function setExternalId(int $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMaxRegistrations(): ?int
    {
        return $this->maxRegistrations;
    }

    public function setMaxRegistrations(int $maxRegistrations): self
    {
        $this->maxRegistrations = $maxRegistrations;

        return $this;
    }

    public function getCurrentRegistrations(): ?int
    {
        return $this->currentRegistrations;
    }

    public function setCurrentRegistrations(int $currentRegistrations): self
    {
        $this->currentRegistrations = $currentRegistrations;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function isFull() {
        return ($this->currentRegistrations >= $this->maxRegistrations);
    }

    public function getCategory(): ?OutingCategory
    {
        return $this->category;
    }

    public function setCategory(?OutingCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getWaitingRegistrations(): ?int
    {
        return $this->waitingRegistrations;
    }

    public function setWaitingRegistrations(?int $waitingRegistrations): self
    {
        $this->waitingRegistrations = $waitingRegistrations;

        return $this;
    }

    public function getVirtual(): ?bool
    {
        return $this->virt;
    }

    public function setVirtual(bool $virt): self
    {
        $this->virt = $virt;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getExternalUrl(): ?string
    {
        return $this->externalUrl;
    }

    public function setExternalUrl(?string $externalUrl): self
    {
        $this->externalUrl = $externalUrl;

        return $this;
    }
}
