<?php

namespace src\controller;

class HomepageController extends AbstractController
{
    public function show($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('homepage', 'homepageTemplate', $pageModulePath);
        $title = 'My-Ecommerce-Demo';
        
        $reviewController = new ReviewController();
        
        $productController = new ProductController();
        $lastAddedProducts = $productController->getLastAddedProductCardGeneratorWithLimit(4);
        foreach ($lastAddedProducts as $productWithImageDto) {
            $productAvgRate = $reviewController->getAvgReviewRateByProductId($productWithImageDto->getProduct()->getId());
            if ($productAvgRate != null) {
                $avgRatingArray[] = $productAvgRate;
            }
        }
        
        require_once($templateFilePath);

    }
}