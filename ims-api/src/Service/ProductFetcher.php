<?php

namespace App\Service;

use App\Dto\ProductListDto;
use App\Entity\Membership;
use App\Repository\ProductRepository;
use App\Repository\Value\ProductListFilter;
use App\Repository\Value\ProductListFilterField;
use App\Repository\Value\ProductListSort;
use App\Repository\Value\ProductListSortField;
use App\Value\ListSortDirection;

class ProductFetcher
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    )
    {
    }

    public function getProducts(
        Membership $membership,
        ProductListDto$productListDto
    )
    {
        return $this->productRepository->findByOrganization(
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
    }
}
