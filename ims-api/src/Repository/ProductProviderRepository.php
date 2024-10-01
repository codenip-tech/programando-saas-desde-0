<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\ProductProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductProviderRepository
{
    private readonly ServiceEntityRepository $entityRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->entityRepository = new ServiceEntityRepository($registry, ProductProvider::class);
    }

    public function save(ProductProvider $tag): void
    {
        $this->entityRepository->getEntityManager()->persist($tag);
        $this->entityRepository->getEntityManager()->flush();
    }

    public function findByOrganization(Organization $organization)
    {
        return $this->entityRepository->findBy(['organization' => $organization]);
    }
}
