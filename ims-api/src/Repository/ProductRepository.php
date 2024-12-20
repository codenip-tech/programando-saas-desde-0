<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\Product;
use App\Repository\Value\ProductListFilter;
use App\Repository\Value\ProductListSort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository
{
    private readonly ServiceEntityRepository $entityRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->entityRepository = new ServiceEntityRepository($registry, Product::class);
    }

    public function countProductsForOrganization(Organization $organization): int
    {
        return $this->entityRepository->count([
            'organization' => $organization,
        ]);
    }

    public function save(Product $product): void
    {
        $this->entityRepository->getEntityManager()->persist($product);
        $this->entityRepository->getEntityManager()->flush();
    }

    public function delete(Product $product): void
    {
        $this->entityRepository->getEntityManager()->remove($product);
        $this->entityRepository->getEntityManager()->flush();
    }

    /**
     * @param Organization $organization
     * @param ProductListSort|null $sort
     * @param ProductListFilter|null $filter
     * @return Product[]
     */
    public function findByOrganization(Organization $organization, ?ProductListSort $sort, ?ProductListFilter $filter)
    {
        $qb = $this->entityRepository->createQueryBuilder('p')
            ->where('p.organization = :organization')
            ->setParameter('organization', $organization->getId());
        if ($sort) {
            $qb->orderBy('p.'.$sort->field->value, $sort->direction->value);
        }
        if ($filter) {
            $qb->andWhere('p.name LIKE :name')
                ->setParameter(':name', '%'.$filter->value.'%');
        }

        return $qb->getQuery()->execute();
    }

    public function findOneByIdAndOrganization(int $id, Organization $organization): ?Product
    {
        return $this->entityRepository->findOneBy(['id' => $id, 'organization' => $organization]);
    }

    public function findAllProductsByOrganization(Organization $organization): array
    {
        return $this->entityRepository->findBy(['organization' => $organization]);
    }
}
