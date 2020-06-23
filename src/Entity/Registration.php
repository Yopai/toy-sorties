<?php

namespace App\Entity;

use App\Repository\RegistrationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegistrationRepository::class)
 */
class Registration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="registrations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity=Appointment::class, inversedBy="registrations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $appointment;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onWaitingList;

    /**
     * @ORM\Column(type="smallint")
     */
    private $count;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): self
    {
        $this->appointment = $appointment;

        return $this;
    }

    public function getOnWaitingList(): ?bool
    {
        return $this->onWaitingList;
    }

    public function setOnWaitingList(bool $onWaitingList): self
    {
        $this->onWaitingList = $onWaitingList;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }
}
