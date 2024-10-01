<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Tag;
use App\Repository\ProductRepository;
use App\Repository\TagRepository;
use App\Value\AccessingMember;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/{id}', name: 'product_get', methods: ['GET'])]
    public function getProduct(
        ProductRepository $productRepository,
        AccessingMember $accessingMember,
        int $id
    )
    {
        $membership = $accessingMember->membership;
        $product = $productRepository->findOneByIdAndOrganization($id, $membership->getOrganization());
        if (!$product) {
            throw $this->createNotFoundException();
        }

        return new JsonResponse([
            'product' => ['id' => $product->getId(), 'name' => $product->getName(), 'tagIds' => array_map(fn(Tag $tag) => $tag->getId(), $product->getTags()->toArray()) ],
        ]);
    }

    #[Route('/{id}', name: 'product_update', methods: ['POST'])]
    public function updateProduct(
        ProductRepository $productRepository,
        TagRepository $tagRepository,
        AccessingMember $accessingMember,
        Request $request,
        int $id
    )
    {
        $membership = $accessingMember->membership;
        $product = $productRepository->findOneByIdAndOrganization($id, $membership->getOrganization());

        if (!$product) {
            throw $this->createNotFoundException();
        }

        $payload = $request->getPayload();
        $productName = $payload->get('name');
        /** @var int[] $tagIds */
        $tagIds = $payload->all()['tagIds'];

        $tags = $tagRepository->findByOrganizationAndIds($membership->getOrganization(), $tagIds);
        $product->setName($productName);
        $product->setTags(new ArrayCollection($tags));
        $productRepository->save($product);

        return new JsonResponse([
            'product' => ['id' => $product->getId(), 'name' => $product->getName()],
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
