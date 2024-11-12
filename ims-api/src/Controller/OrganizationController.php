<?php

namespace App\Controller;

use App\Entity\Membership;
use App\Entity\Organization;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\MembershipRepository;
use App\Repository\OrganizationRepository;
use App\Repository\ProductRepository;
use App\Service\OrganizationAccessChecker;
use App\Service\OrganizationCreator;
use App\Value\MembershipRole;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/organization')]
class OrganizationController extends AbstractController
{
    #[Route('', name: 'organization_create', methods: ['POST'])]
    public function createProduct(
        Request $request,
        OrganizationCreator $organizationCreator,
    ): JsonResponse {
        $payload = $request->getPayload();
        $organizationName = $payload->get('name');
        /** @var User $user */
        $user = $this->getUser();

        $membership = $organizationCreator->create($organizationName, $user);

        return new JsonResponse(
            [
                'id' => $membership->getOrganization()->getId()
            ]
        );
    }

    #[Route('', name: 'organization_list_mine', methods: ['GET'])]
    public function getMyOrganizations(
        OrganizationRepository $organizationRepository
    ): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $organizations = $organizationRepository->findForUser($user->getId());

        return new JsonResponse([
            'organizations' => array_map(fn(Organization $organization) => [
                'id' => $organization->getId(),
                'name' => $organization->getName(),
            ], $organizations)
        ]);
    }
}
