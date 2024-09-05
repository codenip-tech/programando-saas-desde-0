<?php

namespace App\Controller;

use App\Entity\Membership;
use App\Entity\Organization;
use App\Entity\User;
use App\Repository\MembershipRepository;
use App\Repository\OrganizationRepository;
use App\Repository\UserRepository;
use App\Value\MembershipRole;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ApiAuthController extends AbstractController
{
    #[Route('/api/auth/register', name: 'api_auth_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        MembershipRepository $membershipRepository,
    ): JsonResponse
    {
        // @todo Validate username password. Even better here receive validated object
        // @todo Move everything to a user factory
        $payload = $request->getPayload();
        $email = $payload->get('email');
        $plainPassword = $payload->get('password');

        $user = User::createNewWithEmail($email);
        $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
        $organization = new Organization('Personal Organization', $user);
        $membership = new Membership($organization, $user, MembershipRole::ADMIN);

        try {
            $userRepository->save($user);
        } catch (UniqueConstraintViolationException $exception) {
            throw new BadRequestException('Email already in use');
        }
        $membershipRepository->save($membership);

        return new JsonResponse([
            'success' => true
        ]);
    }

    #[Route('/api/auth/login', name: 'api_auth_login', methods: ['POST'])]
    public function login(string $jwtSecret): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception('Invalid user type');
        }

        $payload = [
            'iat' => time(),
            'exp' => time() + 3600 * 24,
            'sub' => $user->getId()
        ];

        return new JsonResponse(
            [
                'token' => JWT::encode($payload, $jwtSecret, 'HS256')
            ],
        );
    }
}
