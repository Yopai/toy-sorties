<?php

namespace App\Entity;

use App\Repository\ExternalSourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExternalSourceRepository::class)
 */
class ExternalSource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $root_url;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $active = false;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $serviceClass;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRootUrl(): ?string
    {
        return $this->root_url;
    }

    public function setRootUrl(string $root_url): self
    {
        $this->root_url = $root_url;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getServiceClass(): ?string
    {
        return $this->serviceClass;
    }

    public function setServiceClass(string $serviceClass): self
    {
        $this->serviceClass = $serviceClass;

        return $this;
    }
}
