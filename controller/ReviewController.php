<?php

namespace controller;

use Router;
use src\entity\Review;
use src\repository\ReviewRepository;

class ReviewController extends AbstractController
{

    public function show(int $productId)
    {

    }

    public function add()
    {
        if (isset($_POST['userId'], $_POST['productId'], $_POST['rating'], $_POST['review'], $_POST['title'])) {
            $em = $this->getEntityManager();
            $url = Router::parse_referer();
            
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

    public function reviewRowGenerator($productId)
    {
        $em = $this->getEntityManager();
        /** @var ReviewRepository $reviewRepository */
        $reviewRepository = $em->getRepository(Review::class);
        $reviewWithUserDto = $reviewRepository->findByProductId($productId);
        if ($reviewWithUserDto) {
            $str = "<div class=\"comments\">";
            foreach ($reviewWithUserDto as $reviewWithUser) {
                $review = $reviewWithUser->getReview();
                $date = $review->getCreatedAt()->format('d/m/Y');
                $str .= $this->reviewRow($review->getTitle(), $review->getReview(), $reviewWithUser->getUserName(), $reviewWithUser->getUserLastName(), $date, $review->getRating());
            }
            $str .= "</div>";
            echo $str;
        } else {
            echo "No reviews";
        }
    }

    public function reviewRow($title, $content, $firstName, $lastName, $date, $rating): string
    {
        $star ="";
        for ($i = 1; $i <= $rating; $i++) {
            $star .= "<i class=\"fas fa-star\"></i>";
        }
        return "<div class=\"d-flex flex-row align-items-center\">
                            <div class=\"d-flex flex-column ml-1\">
                                <div class=\"comment-ratings\">".
                                    $star.
                                    "<span class=\"username\">$firstName  $lastName</span>   
                                    <div class=\"date\"> <span class=\"text-muted\">$date</span> 
                                    </div>
                                </div>
                                <div class=\"review-title\">$title</div>
                                <p class=\"review-content\">$content</p>
                            </div>
                        </div>
                    <hr>";
    }
}