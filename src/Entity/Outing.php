<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OutingRepository::class)
 * @ORM\InheritanceType("JOINED")
 */
class Outing
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=Appointment::class, mappedBy="outing", orphanRemoval=true)
     */
    private $appointments;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;


    /**
     * @ORM\ManyToOne(targetEntity=OutingCategory::class)
     */
    private $category;

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     *
     * @return Outing
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxRegistrations()
    {
        return $this->maxRegistrations;
    }

    /**
     * @param mixed $maxRegistrations
     *
     * @return Outing
     */
    public function setMaxRegistrations($maxRegistrations)
    {
        $this->maxRegistrations = $maxRegistrations;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentRegistrations()
    {
        return $this->currentRegistrations;
    }

    /**
     * @param mixed $currentRegistrations
     *
     * @return Outing
     */
    public function setCurrentRegistrations($currentRegistrations)
    {
        $this->currentRegistrations = $currentRegistrations;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWaitingRegistrations()
    {
        return $this->waitingRegistrations;
    }

    /**
     * @param mixed $waitingRegistrations
     *
     * @return Outing
     */
    public function setWaitingRegistrations($waitingRegistrations)
    {
        $this->waitingRegistrations = $waitingRegistrations;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVirt()
    {
        return $this->virt;
    }

    /**
     * @param mixed $virt
     *
     * @return Outing
     */
    public function setVirt($virt)
    {
        $this->virt = $virt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     *
     * @return Outing
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $maxRegistrations;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $currentRegistrations;

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


    public function __construct()
    {
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

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

    /**
     * @return Collection|Appointment[]
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments[] = $appointment;
            $appointment->setOuting($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->contains($appointment)) {
            $this->appointments->removeElement($appointment);
            // set the owning side to null (unless already changed)
            if ($appointment->getOuting() === $this) {
                $appointment->setOuting(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?Member
    {
        return $this->author;
    }

    public function setAuthor(?Member $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function isFull() {
        return ($this->currentRegistrations >= $this->maxRegistrations);
    }
}
