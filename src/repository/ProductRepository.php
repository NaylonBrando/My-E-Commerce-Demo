<?php

namespace src\repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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
     * @var ProductWithImageDto[]
     */
    public function findProductsWithPaginator($page, $limit):array
    {
        $productWithImagesDtoArray = [];

        /** @var Product[] $products */
        $products = [];

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('p')
            ->from(Product::class, 'p')
            ->orderBy('p.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();
        $products = $qb->getQuery()->getResult();

        foreach ($products as $product) {
            $images = $this->getEntityManager()->getRepository(ProductImage::class)->findBy(['productId' => $product->getId()]);
            $productWithImagesDto = new ProductWithImageDto();
            $productWithImagesDto->setProduct($product);
            if($images){
                $productWithImagesDto->setImages($images);
            }
            $productWithImagesDtoArray[] = $productWithImagesDto;
        }
        return $productWithImagesDtoArray;
    }

    /**
     * @var ProductWithImageDto[]
     */
    public function findProductsByCartUserId(int $cartUserId): array
    {
        $productWithImagesDtoArray = [];

        /** @var Product[] $products */
        $products = [];

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from('src\entity\Product', 'p')
            ->join(Cart::class, 'c', Join::WITH, 'c.productId = p.id')
            ->where('c.userId = :userId')
            ->setParameter('userId', $cartUserId);
        $products = $qb->getQuery()->getResult();

        foreach ($products as $product) {
            $images = $this->getEntityManager()->getRepository(ProductImage::class)->findBy(['productId' => $product->getId()]);
            $productWithImagesDto = new ProductWithImageDto();
            $productWithImagesDto->setProduct($product);
            if($images){
                $productWithImagesDto->setImages($images);
            }
            $productWithImagesDtoArray[] = $productWithImagesDto;
        }
        return $productWithImagesDtoArray;

    }


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
}
