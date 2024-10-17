<?php

namespace App\Controller;

use App\Dto\ProductListDto;
use App\Entity\Product;
use App\Entity\Tag;
use App\Repository\ProductRepository;
use App\Repository\TagRepository;
use App\Repository\Value\ProductListFilter;
use App\Repository\Value\ProductListFilterField;
use App\Repository\Value\ProductListSort;
use App\Repository\Value\ProductListSortField;
use App\Value\AccessingMember;
use App\Value\ListSort;
use App\Value\ListSortDirection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/product')]
class ProductController extends AbstractController
{
    #[Route('/import', name: 'product_import', methods: ['POST'])]
    public function importProducts(
        Request $request,
        AccessingMember $accessingMember,
        ProductRepository $productRepository,
    ): JsonResponse {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        $fileContent = trim(file_get_contents($file->getPathname()));
        $organization = $accessingMember->membership->getOrganization();
        foreach (explode("\n", $fileContent) as $line) {
            $fields = explode(',', $line);
            if (in_array('id', $fields)) {
                if ($fields[0] !== 'id' || $fields[1] !== 'name' || $fields[2] !== 'action') {
                    throw new BadRequestException('Invalid columns');
                }
                continue;
            }

            list($id, $name, $action) = $fields;
            if ($action === 'create' && $id !== '') {
                throw new BadRequestException('Provided id for create');
            }
            if (in_array($action, ['update', 'delete']) && $id === '') {
                throw new BadRequestException('Missing id for update or deleted');
            }

            if (!in_array($action, ['create', 'update', 'delete'])) {
                throw new BadRequestException('Invalid action');
            }

            if ($action === 'create') {
                $product = new Product($organization, $name);
                $productRepository->save($product);
            } elseif ($action === 'update') {
                $product = $productRepository->findOneByIdAndOrganization((int) $id, $organization);
                $product->setName($name);
                $productRepository->save($product);
            } else {
                $product = $productRepository->findOneByIdAndOrganization((int) $id, $organization);
                $productRepository->delete($product);
            }
        }
        return new JsonResponse([]);
    }

    #[Route('/list', name: 'product_list', methods: ['POST'])]
    public function listProducts(
        ProductRepository $productRepository,
        AccessingMember $accessingMember,
        Request $request,
        #[MapRequestPayload] ProductListDto $productListDto,
    )
    {
        $membership = $accessingMember->membership;
        $products = $productRepository->findByOrganization(
            $membership->getOrganization(),
            $productListDto->sort ? new ProductListSort(
                ProductListSortField::from($productListDto->sort->field),
                ListSortDirection::from($productListDto->sort->direction)
            ) : null,
            $productListDto->filter ? new ProductListFilter(
                ProductListFilterField::from($productListDto->filter->field),
                $productListDto->filter->value
            ) : null
        );

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
