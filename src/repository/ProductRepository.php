<?php

namespace src\repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use src\entity\Brand;
use src\entity\Category;
use src\entity\Product;
use src\entity\ProductToCategory;
use src\dto\ProductDetailDto;

class ProductRepository extends EntityRepository
{
    public function findAllProductsWithDetails():array {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('p.id', 'p.title', 'p.slug', 'b.name as brandName', 'c.name categoryName', 'p.description', 'p.price', 'p.quantity', 'p.createdAt', 'p.isActive', 'p.stockNumber')
            ->from(Product::class, 'p')
            ->innerJoin(
                Brand::class,
                'b',
                Join::WITH,
                'b.id = p.brandId',
            )
            ->innerJoin(
                ProductToCategory::class,
                'ptc',
                Join::WITH,
                'ptc.productId = p.id',
            )
            ->innerJoin(
                Category::class,
                'c',
                Join::WITH,
                'ptc.categoryId = c.id',
            );

        $result =  $qb->getQuery()->getResult();
        $productDetailDtoArray = [];
        foreach ($result as $row) {
            $productDetailDto = new ProductDetailDto();
            $productDetailDto->setId($row['id']);
            $productDetailDto->setTitle($row['title']);
            $productDetailDto->setSlug($row['slug']);
            $productDetailDto->setDescription($row['description']);
            $productDetailDto->setPrice($row['price']);
            $productDetailDto->setCategoryName($row['categoryName']);
            $productDetailDto->setBrandName($row['brandName']);
            $productDetailDto->setQuantity($row['quantity']);
            $productDetailDto->setIsActive($row['isActive']);
            $productDetailDto->setCreatedAt($row['createdAt']);
            $productDetailDto->setStockNumber($row['stockNumber']);

            $productDetailDtoArray[] = $productDetailDto;
        }
        return $productDetailDtoArray;
    }
}
