<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TagRepository
{
    /**
     * @var ServiceEntityRepository<Tag>
     */
    private readonly ServiceEntityRepository $entityRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->entityRepository = new ServiceEntityRepository($registry, Tag::class);
    }

    public function save(Tag $tag): void
    {
        $this->entityRepository->getEntityManager()->persist($tag);
        $this->entityRepository->getEntityManager()->flush();
    }

    /**
     * @param Organization $organization
     * @return Tag[]
     */
    public function findByOrganization(Organization $organization)
    {
        return $this->entityRepository->findBy(['organization' => $organization]);
    }

    /**
     * @param Organization $organization
     * @param int[] $ids
     * @return Tag[]
     */
    public function findByOrganizationAndIds(Organization $organization, array $ids)
    {
        return $this->entityRepository->findBy(['organization' => $organization, 'id' => $ids]);
    }

    public function findOneByIdAndOrganization(int $id, Organization $organization): ?Tag
    {
        return $this->entityRepository->findOneBy(['organization' => $organization, 'id' => $id]);
    }
}
