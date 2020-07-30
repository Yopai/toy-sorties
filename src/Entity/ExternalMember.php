<?php

namespace App\Entity;

use App\Repository\ExternalMemberRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExternalMemberRepository::class)
 * @ORM\Table(uniqueConstraints={
 *        @ORM\UniqueConstraint(name="external_member_unique",
 *            columns={"source_id", "username"})
 *    }*
 * )
 */
class ExternalMember extends Member
{
    /**
     * @ORM\ManyToOne(targetEntity=ExternalSource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $source;

    public function getSource(): ?ExternalSource
    {
        return $this->source;
    }

    public function setSource(?ExternalSource $source): self
    {
        $this->source = $source;

        return $this;
    }
}
