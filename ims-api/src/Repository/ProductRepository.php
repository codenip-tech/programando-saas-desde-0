<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository
{
    private readonly ServiceEntityRepository $entityRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->entityRepository = new ServiceEntityRepository($registry, Product::class);
    }

    public function save(Product $product): void
    {
        $this->entityRepository->getEntityManager()->persist($product);
        $this->entityRepository->getEntityManager()->flush();
    }

    public function findByOrganization(Organization $organization)
    {
        return $this->entityRepository->findBy(['organization' => $organization]);
    }

    public function findOneByIdAndOrganization(int $id, Organization $organization): ?Product
    {
        return $this->entityRepository->findOneBy(['id' => $id, 'organization' => $organization]);
    }
}
