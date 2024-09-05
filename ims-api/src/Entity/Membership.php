<?php

namespace App\Entity;

use App\Repository\MembershipRepository;
use App\Value\MembershipRole;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembershipRepository::class)]
#[ORM\Table(name: '`memberships`')]
#[ORM\UniqueConstraint(name: 'memberships_org_user_id_uniq', columns: ['organization_id', 'user_id'])]
final class Membership
{
    public function __construct(Organization $organization, User $user, MembershipRole $role) {
        $this->organization = $organization;
        $this->user = $user;
        $this->role = $role;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue("IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private MembershipRole $role;

    #[ORM\ManyToOne(targetEntity: 'User', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: 'Organization', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private Organization $organization;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }
}
