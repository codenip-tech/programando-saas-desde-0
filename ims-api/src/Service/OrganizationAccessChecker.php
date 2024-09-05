<?php

namespace App\Service;

use App\Entity\Membership;
use App\Entity\User;
use App\Repository\MembershipRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class OrganizationAccessChecker
{
    public function __construct(private readonly MembershipRepository $membershipRepository)
    {
    }

    public function checkAccess(Request $request, User $user): Membership
    {
        $organizationId = $request->headers->get('x-organization-id');
        if (!$organizationId) {
            throw new BadRequestException('Missing x-organization-id');
        }

        try {
            $membership = $this->membershipRepository->findOneByOrganizationAndUserId($organizationId, $user->getId());
        } catch (NoResultException $exception) {
            throw new NotFoundHttpException('Organization not found');
        }

        return $membership;
    }
}
