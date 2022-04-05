<?php

namespace src\repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use src\dto\ProductDetailDto;
use src\dto\ProductWithImageDto;
use src\entity\Brand;
use src\entity\Cart;
use src\entity\Category;
use src\entity\Product;
use src\entity\ProductImage;
use src\entity\ProductToCategory;

class ProductRepository extends EntityRepository
{

    /**
     * @return ProductWithImageDto[]
     */
    public function getLastProductsByLimit(int $limit): array
    {
        $productWithImagesDtoArray = [];
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from(Product::class, 'p')
            ->where('p.isActive = 1')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery();

        return $this->extracted($qb, $productWithImagesDtoArray);
    }

    /**
     * @return ProductWithImageDto[]
     */
    public function findProductsByCategoryName($page, $limit, $categoryName = null): array
    {
        $productWithImagesDtoArray = [];

        /** @var Product[] $products */

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from(Product::class, 'p')
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
            )
            ->where('c.name IN (:categoryName)', 'p.isActive = 1')
            ->setParameter('categoryName', $categoryName)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return $this->extracted($qb, $productWithImagesDtoArray);
    }

    public function countProductsByCategoryName($categoryName): int
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('COUNT(p)')
            ->from(Product::class, 'p')
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
            )
            ->where('c.name IN (:categoryName)')
            ->setParameter('categoryName', $categoryName);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return ProductWithImageDto[]
     */
    public function findProductsByCartUserId(int $cartUserId): array
    {
        $productWithImagesDtoArray = [];

        /** @var Product[] $products */

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from(Product::class, 'p')
            ->innerjoin(Cart::class, 'c', Join::WITH, 'c.productId = p.id')
            ->where('c.userId = :userId', 'p.isActive = 1')
            ->setParameter('userId', $cartUserId);
        return $this->extracted($qb, $productWithImagesDtoArray);

    }

    /**
     * @return ProductDetailDto[]
     */
    public function findAllProductsWithDetails(): array
    {

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
            )
            ->orderBy('p.id', 'ASC');

        $result = $qb->getQuery()->getResult();
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

    /**
     * @param QueryBuilder $qb
     * @param array $productWithImagesDtoArray
     * @return array
     */
    public function extracted(QueryBuilder $qb, array $productWithImagesDtoArray): array
    {
        $products = $qb->getQuery()->getResult();

        foreach ($products as $product) {
            $images = $this->getEntityManager()->getRepository(ProductImage::class)->findBy(['productId' => $product->getId()]);
            $productWithImagesDto = new ProductWithImageDto();
            $productWithImagesDto->setProduct($product);
            if ($images) {
                $productWithImagesDto->setImages($images);
            }
            $productWithImagesDtoArray[] = $productWithImagesDto;
        }
        return $productWithImagesDtoArray;
    }
}
