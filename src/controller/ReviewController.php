<?php

namespace src\controller;

use Router;
use src\dto\ReviewWithUserDto;
use src\entity\Review;
use src\repository\ReviewRepository;

class ReviewController extends AbstractController
{
    public function add()
    {
        if (isset($_SESSION['user_id'], $_POST['productId'], $_POST['rating'], $_POST['review'], $_POST['title'])) {
            $em = $this->getEntityManager();
            $url = Router::parseReferer();
            $review = new Review();
            $review->setUserId($_POST['userId']);
            $review->setProductId($_POST['productId']);
            $review->setRating($_POST['rating']);
            $review->setTitle($_POST['title']);
            $review->setReview($_POST['review']);
            $em->persist($review);
            $em->flush();
            header('Location: ' . $url);

        } else {
            header('Location:/');

        }
    }

    /**
     * @param $productId
     * @return ReviewWithUserDto|null
     */
    public function getByProductId($productId): ?array
    {
        $em = $this->getEntityManager();
        /** @var ReviewRepository $reviewRepository */
        $reviewRepository = $em->getRepository(Review::class);
        $reviewWithUserDto = $reviewRepository->findByProductId($productId);
        if ($reviewWithUserDto) {
            return $reviewWithUserDto;
        }
        return null;
    }

    public function getAvgReviewRateByProductId(int $productId): ?array
    {
        $em = $this->getEntityManager();
        /** @var ReviewRepository $reviewRepository */
        $reviewRepository = $em->getRepository(Review::class);
        $avgReviewRate = $reviewRepository->getAvgReviewRateByProductId($productId);
        if ($avgReviewRate['rateCount'] > 0 & $avgReviewRate['avgRate'] != null) {
            return $avgReviewRate;
        }
        return null;
    }
}