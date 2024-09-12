<?php

namespace App\ValueResolver;

use App\Entity\User;
use App\Service\OrganizationAccessChecker;
use App\Value\AccessingMember;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AccessingMemberValueResolver implements ValueResolverInterface
{
    public function __construct(
        private OrganizationAccessChecker $organizationAccessChecker,
        private TokenStorageInterface $tokenStorage,
    )
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (
            !$argumentType
            || $argumentType !== AccessingMember::class
        ) {
            return [];
        }

        if (null === $token = $this->tokenStorage->getToken()) {
            return [null];
        }
        /** @var User $user */
        $user = $token->getUser();
        if (!$user) {
            return [null];
        }

        $membership = $this->organizationAccessChecker->checkAccess($request, $user);
        return [new AccessingMember($membership)];
    }
}
