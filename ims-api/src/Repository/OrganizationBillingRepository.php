<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\OrganizationBilling;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrganizationBillingRepository
{
    private readonly ServiceEntityRepository $entityRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->entityRepository = new ServiceEntityRepository($registry, OrganizationBilling::class);
    }

    public function findOneForOrganization(Organization $organization): OrganizationBilling
    {
        return $this->entityRepository->findOneBy(['organization' => $organization]);
    }

    public function findOneForCustomer(string $customerId): OrganizationBilling
    {
        return $this->entityRepository->findOneBy(['customerId' => $customerId]);
    }

    public function save(OrganizationBilling $organizationBilling): void
    {
        $this->entityRepository->getEntityManager()->persist($organizationBilling);
        $this->entityRepository->getEntityManager()->flush();
    }
}
