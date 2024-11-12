<?php

namespace App\Service;

use App\Entity\Membership;
use App\Entity\Organization;
use App\Entity\OrganizationBilling;
use App\Entity\User;
use App\Repository\MembershipRepository;
use App\Repository\OrganizationBillingRepository;
use App\Value\MembershipRole;

readonly class OrganizationCreator
{
    public function __construct(
        private MembershipRepository $membershipRepository,
        private StripeApi $stripeApi,
        private OrganizationBillingRepository $organizationBillingRepository,
    )
    {
    }

    public function create(string $organizationName, User $owner): Membership
    {
        $organization = new Organization($organizationName, $owner);
        $membership = new Membership($organization, $owner, MembershipRole::ADMIN);
        $this->membershipRepository->save($membership);

        $customerId = $this->stripeApi->createCustomer(
            $organizationName,
            $owner->getEmail(),
            $organization->getId()
        );
        $organizationBilling = new OrganizationBilling(
            $organization,
            $customerId
        );
        $this->organizationBillingRepository->save($organizationBilling);

        return $membership;
    }
}
