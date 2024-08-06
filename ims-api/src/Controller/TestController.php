<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/api/ping', name: 'ping')]
    public function ping() {
        return new JsonResponse(
            [
                'pong' => true
            ]
        );
    }

}
