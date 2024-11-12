<?php

namespace App\Entity;

use App\Repository\OrganizationBillingRepository;
use App\Value\OrganizationBillingStatus;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganizationBillingRepository::class)]
#[ORM\Table(name: '`organization_billings`')]
final class OrganizationBilling
{
    public function __construct(Organization $organization, string $customerId) {
        $this->customerId = $customerId;
        $this->organization = $organization;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue("IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $customerId;

    #[ORM\Column(length: 30)]
    private OrganizationBillingStatus $status = OrganizationBillingStatus::INACTIVE;

    #[ORM\OneToOne(targetEntity: 'Organization')]
    #[ORM\JoinColumn(nullable: false)]
    private Organization $organization;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerId(): string {
        return $this->customerId;
    }

    public function setCustomerId(string $customerId): self {
        $this->customerId = $customerId;
        return $this;
    }

    public function getOrganization(): Organization {
        return $this->organization;
    }

    public function isActive(): bool
    {
        return $this->status === OrganizationBillingStatus::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === OrganizationBillingStatus::INACTIVE;
    }

    public function activate(): void
    {
        $this->status = OrganizationBillingStatus::ACTIVE;
    }

    public function deactivate(): void
    {
        $this->status = OrganizationBillingStatus::INACTIVE;
    }
}
