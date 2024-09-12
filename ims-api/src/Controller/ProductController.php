<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Service\OrganizationAccessChecker;
use App\Value\MembershipRole;
use App\Value\AccessingMember;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/product')]
class ProductController extends AbstractController
{
    #[Route('', name: 'product_list', methods: ['GET'])]
    public function listProducts(
        ProductRepository $productRepository,
        AccessingMember $accessingMember,
    )
    {
        $membership = $accessingMember->membership;
        $products = $productRepository->findByOrganization($membership->getOrganization());

        return new JsonResponse([
            'products' => array_map(fn (Product $product) => ['id' => $product->getId(), 'name' => $product->getName()], $products)
        ]);
    }

    #[Route('', name: 'product_create', methods: ['POST'])]
    public function createProduct(
        Request $request,
        AccessingMember $accessingMember,
        ProductRepository $productRepository,
    ): JsonResponse {
        $payload = $request->getPayload();
        $productName = $payload->get('name');

        $membership = $accessingMember->membership;
        $product = new Product($membership->getOrganization(), $productName);
        $productRepository->save($product);

        return new JsonResponse(
            [
                'id' => $product->getId()
            ]
        );
    }

}
