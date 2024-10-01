<?php

namespace App\Entity;

use App\Repository\ProductProviderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductProviderRepository::class)]
#[ORM\Table(name: '`product_providers`')]
final class ProductProvider
{
    public function __construct(Organization $organization, string $name) {
        $this->organization = $organization;
        $this->name = $name;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue("IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $name;

    #[ORM\ManyToOne(targetEntity: 'Organization', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private Organization $organization;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
