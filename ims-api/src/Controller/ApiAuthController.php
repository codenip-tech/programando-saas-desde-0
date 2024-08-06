<?php

namespace App\Controller;

use App\Entity\User;
use Cassandra\Date;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiAuthController extends AbstractController
{


    #[Route('/api/auth/login', name: 'api_auth_login', methods: ['POST'])]
    public function login(): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception('Invalid user type');
        }

        $key = 'example_key';
        $payload = [
            'iat' => time(),
            'exp' => time() + 3600 * 24,
            'sub' => $user->getId()
        ];


        return new JsonResponse(
            [
                'token' => JWT::encode($payload, $key, 'HS256')
            ],
        );
    }
}
