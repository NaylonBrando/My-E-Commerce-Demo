<?php

namespace admin\controller;

use admin\helper\FileHelper;
use src\entity\Product;
use src\entity\ProductImage;
use src\repository\ProductImageRepository;

class ProductImageController extends AdminAbstractController
{
    public function show(string $pageModulePath, $id)
    {
        $em = $this->getEntityManager();
        /** @var Product $product */
        $product = $em->find(Product::class, $id[1]);

        if ($product) {
            $title = $product->getTitle() . ' images';
            $slug = 'Slug: ' . $product->getSlug();
            $pageModule = $pageModulePath;
            $templateFilePath = str_replace('productImage', 'adminPanelTemplate', $pageModulePath);
        } else {
            $templateFilePath = str_replace('productImage', '404', $pageModulePath);
        }
        require_once($templateFilePath);
        if (isset($_SESSION['imageUploadError'])) {
            unset($_SESSION['imageUploadError']);
        }

    }

    public function add(int $productId)
    {
        $maxFile = 5;
        $em = $this->getEntityManager();
        /** @var ProductImageRepository $entityRepository */
        $entityRepository = $em->getRepository(ProductImage::class);
        /** @var ProductImage[] $entityResult */
        $entityResult = $entityRepository->findByProductId($productId);
        if ($entityResult) {
            $maxFile = $maxFile - count($entityResult);
        }

        $uploadResult = FileHelper::upload('images', '../upload/', 5, $maxFile);
        $withoutImage = strtolower('You did not provide any files.');

        if (strcmp(strtolower($uploadResult['errors'][0]), $withoutImage) == 0) {
            header('location: /admin/product');
        } elseif ($uploadResult['fileNames']) {
            foreach ($uploadResult['fileNames'] as $fileName) {
                $productImage = new ProductImage();
                $productImage->setProductId($productId);
                $productImage->setPath($fileName);
                $em->persist($productImage);
            }
            $em->flush();
            if ($uploadResult['errors']) {
                $_SESSION['imageUploadError'] = $uploadResult['errors'];
            }
            header('location: /admin/product/image/' . $productId);
        } elseif ($uploadResult['errors']) {
            $_SESSION['imageUploadError'] = $uploadResult['errors'];
            header('location: /admin/product/image/' . $productId);
        }

    }

    public function delete(int $id)
    {
        $em = $this->getEntityManager();
        /** @var ProductImage $productImage */
        $productImage = $em->find(ProductImage::class, $id);
        if (isset($_SESSION['imageUploadError'])) {
            unset($_SESSION['imageUploadError']);
        }
        if ($productImage) {

            $em->remove($productImage);
            $em->flush();
            if (!FileHelper::delete('../upload/', $productImage->getPath())) {
                $_SESSION['imageDeleteError'] = 'Error occurred while deleting file';
            }
            header('location: /admin/product/image/' . $productImage->getProductId());

        } else {
            $page404 = '../admin/view/404.php';
            require_once($page404);
        }

    }

    public function deleteAll(int $productId): bool
    {
        $em = $this->getEntityManager();
        $productImages = $em->getRepository(ProductImage::class)->findBy(['productId' => $productId]);
        if ($productImages) {
            foreach ($productImages as $productImage) {
                if (FileHelper::delete('../upload/', $productImage->getPath())) {
                    $em->remove($productImage);
                }
            }
            $em->flush();
        }
        return true;
    }

    public function setThumbnail($imageId)
    {

        $em = $this->getEntityManager();
        /** @var ProductImage $selectedImageForThumbnail */
        $selectedImageForThumbnail = $em->find(ProductImage::class, $imageId);

        if ($selectedImageForThumbnail) {
            /** @var ProductImage[] $productImages */
            $productImages = $em->getRepository(ProductImage::class)->findByProductId($selectedImageForThumbnail->getProductId());

            foreach ($productImages as $productImage) {
                if ($productImage->getIsThumbnail() == true) {
                    $productImage->setIsThumbnail(false);
                }
            }
            $selectedImageForThumbnail->setIsThumbnail(true);
            $em->persist($productImage);
            $em->persist($selectedImageForThumbnail);
            $em->flush();
            header('location: /admin/product/image/' . $productImage->getProductId());

        }

    }

    public function imageCards($productId)
    {
        $em = $this->getEntityManager();
        /** @var ProductImageRepository $entityRepository */
        $entityRepository = $em->getRepository(ProductImage::class);
        /** @var ProductImage[] $entityResult */
        $entityResult = $entityRepository->findByProductId($productId);
        foreach ($entityResult as $row) {
            echo self::imageCard('/../upload/' . $row->getPath(), $row->getId(), $row->getIsThumbnail());
        }
    }

    public function imageCard($imagePath, $imageId, $isThumbnail): string
    {
        if ($isThumbnail == false) {

            $element = "
            <div class=\"col-md-3 pb-3\">
            <div class=\"card\">
            <img src=\"$imagePath\" class=\"card-img-top product_card_image\" alt=\"...\">
            <div class=\"card-body\">
            <a href=\"/admin/check-delete-product-image/$imageId\" class=\"btn btn-danger btn-sm\">Delete</a>
            <a href=\"/admin/check-set-thumbnail-product-image/$imageId\" class=\"btn btn-secondary btn-sm\">Set Thumbnail</a>
            </div>
            </div>
            </div>
            ";

        } else {

            $element = "
            <div class=\"col-md-3 pb-3\">
            <div class=\"card\">
            <img src=\"$imagePath\" class=\"card-img-top product_card_image\" alt=\"...\">
            <div class=\"card-body\">
            <a href=\"/admin/check-delete-product-image/$imageId\" class=\"btn btn-danger btn-sm\">Delete</a>
            </div>
            </div>
            </div>
            ";

        }

        return $element;
    }
}