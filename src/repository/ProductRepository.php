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
use src\entity\Review;

class ProductRepository extends EntityRepository
{
    /**
     * @return ProductWithImageDto[]
     */
    public function getLastProductsByLimit(int $limit): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from(Product::class, 'p')
            ->where('p.isActive = 1')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery();

        return $this->extracted($qb);
    }

    /**
     * @param QueryBuilder $qb
     * @return array
     */
    public function extracted(QueryBuilder $qb): array
    {
        $productWithImagesDtoArray = [];
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

    public function findProductById(int $id): ?ProductWithImageDto
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from(Product::class, 'p')
            ->where('p.id = :id', 'p.isActive = 1')
            ->setParameter('id', $id);
        $product = $qb->getQuery()->getOneOrNullResult();
        if ($product === null) {
            return null;
        }
        $images = $this->getEntityManager()->getRepository(ProductImage::class)->findBy(['productId' => $product->getId()]);
        $productWithImagesDto = new ProductWithImageDto();
        $productWithImagesDto->setProduct($product);
        if ($images) {
            $productWithImagesDto->setImages($images);
        }
        return $productWithImagesDto;

    }

    /**
     * @return ProductWithImageDto[]
     */
    public function findProductsBySearchTerm($page, $limit, string $searchTerm, $rate, $price): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
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
            ->where('p.title LIKE :searchTerm')
            ->orWhere('c.name LIKE :searchTerm')
            ->orWhere('b.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->orderBy('p.id', 'ASC');
        if ($price) {
            if ($price === 'desc') {
                $qb->orderBy('p.price', 'desc');
            } elseif ($price === 'asc') {
                $qb->orderBy('p.price', 'asc');
            }
        }
        if ($rate) {
            $qb->leftJoin(
                Review::class,
                'r',
                Join::WITH,
                'p.id = r.productId',
            )
                ->groupBy('p.id');
            if ($rate === 'desc') {
                $qb->orderBy('AVG(r.rating)', 'desc');
            } elseif ($rate === 'asc') {
                $qb->orderBy('AVG(r.rating)', 'asc');
            }
        }

        return $this->extracted($qb);
    }

    /**
     * @return ProductWithImageDto[]
     */
    public function findProductsByIdArray(array $ids): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from(Product::class, 'p')
            ->where('p.id IN (:id)', 'p.isActive = 1')
            ->setParameter('id', $ids);
        return $this->extracted($qb);
    }

    /**
     * @return ProductWithImageDto[]
     */
    public function findProductsByCategoryName($categoryName, $page, $limit, $rate = null, $price = null): array
    {
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
        if ($price) {
            if ($price === 'desc') {
                $qb->orderBy('p.price', 'desc');
            } elseif ($price === 'asc') {
                $qb->orderBy('p.price', 'asc');
            }
        }
        if ($rate) {
            $qb->leftJoin(
                Review::class,
                'r',
                Join::WITH,
                'p.id = r.productId',
            )
                ->groupBy('p.id');
            if ($rate === 'desc') {
                $qb->orderBy('AVG(r.rating)', 'desc');
            } elseif ($rate === 'asc') {
                $qb->orderBy('AVG(r.rating)', 'asc');
            }
        }

        return $this->extracted($qb);
    }

    public function countProducts(bool $isActive = null): int
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('COUNT(p.id)')
            ->from(Product::class, 'p');
        if ($isActive !== null) {
            if ($isActive) {
                $qb->where('p.isActive = 1');
            } else {
                $qb->where('p.isActive = 0');
            }
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countProductsByCategoryName($categoryName, bool $isActive = null): int
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
            ->where('c.name IN (:categoryName)');
        if ($isActive !== null) {
            if ($isActive) {
                $qb->andWhere('p.isActive = 1');
            } else {
                $qb->andWhere('p.isActive = 0');
            }
        }
        $qb->setParameter('categoryName', $categoryName);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countProductsBySearchTerm(string $searchTerm, bool $isActive = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('COUNT(p)')
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
            ->where('p.title LIKE :searchTerm')
            ->orWhere('c.name LIKE :searchTerm')
            ->orWhere('b.name LIKE :searchTerm');
        if ($isActive !== null) {
            if ($isActive) {
                $qb->andWhere('p.isActive = 1');
            } else {
                $qb->andWhere('p.isActive = 0');
            }
        }
        $qb->setParameter('searchTerm', '%' . $searchTerm . '%');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return ProductWithImageDto[]
     */
    public function findProductsByCartUserId(int $cartUserId): array
    {
        /** @var Product[] $products */

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from(Product::class, 'p')
            ->innerJoin(Cart::class, 'c', Join::WITH, 'c.productId = p.id')
            ->where('c.userId = :userId', 'p.isActive = 1')
            ->setParameter('userId', $cartUserId);
        return $this->extracted($qb);

    }

    /**
     * @param int $pageNumber
     * @param int $limit
     * @return ProductDetailDto[]|null
     */
    public function findProductsWithDetails(int $pageNumber, int $limit): ?array
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('p', 'b.name as brandName', 'c.name categoryName')
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
            ->orderBy('p.id', 'ASC')
            ->setFirstResult(($pageNumber - 1) * $limit)
            ->setMaxResults($limit);

        return $this->productDetailExtracted($qb);
    }

    /**
     * @param QueryBuilder $qb
     * @return array|null
     */
    public function productDetailExtracted(QueryBuilder $qb): ?array
    {
        $result = $qb->getQuery()->getResult();
        $productDetailDtoArray = [];
        foreach ($result as $row) {
            $productDetailDto = new ProductDetailDto();
            $productDetailDto->setProduct($row[0]);
            $productDetailDto->setCategoryName($row['categoryName']);
            $productDetailDto->setBrandName($row['brandName']);
            $productDetailDtoArray[] = $productDetailDto;
        }
        if (empty($productDetailDtoArray)) {
            return null;
        }
        return $productDetailDtoArray;
    }

    /**
     * @return ProductDetailDto[]|null
     */
    public function findProductsWithDetailsBySearchTerm(string $searchTerm, int $pageNumber, int $limit): ?array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('p', 'b.name as brandName', 'c.name categoryName')
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
            ->where('p.title LIKE :searchTerm')
            ->orWhere('c.name LIKE :searchTerm')
            ->orWhere('b.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('p.id', 'ASC')
            ->setFirstResult(($pageNumber - 1) * $limit)
            ->setMaxResults($limit);

        return $this->productDetailExtracted($qb);
    }
}
