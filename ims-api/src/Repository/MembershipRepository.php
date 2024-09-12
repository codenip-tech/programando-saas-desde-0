<?php

namespace App\Repository;

use App\Entity\Membership;
use App\Entity\Organization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @extends ServiceEntityRepository<Membership>
 */
class MembershipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Membership::class);
    }

    public function save(Membership $membership): void
    {
        $this->getEntityManager()->persist($membership);
        $this->getEntityManager()->flush();
    }

    public function findOneByOrganizationAndUserId(string $organizationId, string $userId): Membership | null
    {
        return $this->createQueryBuilder('m')
            ->select('m')
            ->addSelect('o')
            ->addSelect('user')
            ->join('m.organization', 'o')
            ->join('m.user', 'u')
            ->where('m.organization = :organizationId AND m.user = :userId')
            ->setParameter('organizationId', $organizationId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleResult();
    }
}
