<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
#[ORM\Table(name: '`organizations`')]
final class Organization
{
    public function __construct(string $name, User $owner) {
        $this->name = $name;
        $this->owner = $owner;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue("IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: 'User', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private User $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getOwner(): User {
        return $this->owner;
    }
}
