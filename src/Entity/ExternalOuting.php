<?php

namespace App\Entity;

use App\Repository\ExternalOutingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExternalOutingRepository::class)
 */
class ExternalOuting extends Outing
{
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
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $externalUrl;

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
