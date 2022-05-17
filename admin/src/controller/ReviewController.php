<?php

namespace admin\src\controller;

use src\dto\ReviewWithUserDto;
use src\entity\Review;
use src\repository\ReviewRepository;

class ReviewController extends AdminAbstractController
{
    public function show($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('review', 'adminPanelTemplate', $pageModulePath);
        $title = 'Review';

        $reviews = $this->getReviewsByStatus(true);
        require_once($templateFilePath);
    }

    /**
     * @param bool $status
     * @return ReviewWithUserDto[]|null
     */
    public function getReviewsByStatus(bool $status): ?array
    {
        $em = $this->getEntityManager();
        /* @var $reviewRepository ReviewRepository */
        $reviewRepository = $em->getRepository(Review::class);
        if ($status) {
            $reviews = $reviewRepository->findByStatus(0);
        } else {
            $reviews = $reviewRepository->findByStatus(1);
        }
        if ($reviews) {
            return $reviews;
        } else {
            return null;
        }
    }

    public function approveReview($reviewId)
    {
        $em = $this->getEntityManager();
        $reviewRepository = $em->getRepository(Review::class);
        $review = $reviewRepository->findOneBy(['id' => $reviewId, 'status' => false]);
        if ($review) {
            $review->setStatus(1);
            $em->persist($review);
            $em->flush();
            header('Location: /admin/review');
        } else {
            $page404 = '../admin/view/404.php';
            require_once($page404);
        }
    }

    public function approveSelectedReviews()
    {
        if (isset($_POST['approve'])) {
            $em = $this->getEntityManager();
            $idArray = $_POST['reviewId'];
            $reviewRepository = $em->getRepository(Review::class);
            foreach ($idArray as $id) {
                $review = $reviewRepository->findOneBy(['id' => $id, 'status' => false]);
                if ($review) {
                    $review->setStatus(1);
                    $em->persist($review);
                }
            }
            $em->flush();
            header('Location: /admin/review');

        }
    }

    public function delete($reviewId)
    {
        $em = $this->getEntityManager();
        $reviewRepository = $em->getRepository(Review::class);
        $review = $reviewRepository->findOneBy(['id' => $reviewId]);
        if ($review) {
            $em->remove($review);
            $em->flush();
            header('Location: /admin/review');
        } else {
            $page404 = '../admin/view/404.php';
            require_once($page404);
        }
    }

    public function deleteReviewsByProductId($productId)
    {
        $em = $this->getEntityManager();
        /* @var $reviewRepository ReviewRepository */
        $reviewRepository = $em->getRepository(Review::class);
        $reviews = $reviewRepository->findOneBy(['productId' => $productId]);
        if ($reviews) {
            $reviewRepository->deleteReviewsByProductId($productId);
        }
    }

    public function deleteSelectedReviews()
    {
        if (isset($_POST['delete'])) {
            $em = $this->getEntityManager();
            $idArray = $_POST['reviewId'];
            $reviewRepository = $em->getRepository(Review::class);
            foreach ($idArray as $id) {
                $review = $reviewRepository->findOneBy(['id' => $id]);
                if ($review) {
                    $em->remove($review);
                }
            }
            $em->flush();
            header('Location: /admin/review');
        }
    }

    public function reviewRow($id, $name, $lastName, $email, $date, $title, $review, $rating, $productTitle, $productSlug): string
    {
        return "
        <tr>
                    <td><input type=\"checkbox\" name=\"reviewId[]\" value=\"$id\"></td>
                    <td>$email $name $lastName</td>
                    <td>$date</td>
                    <td><a href='/product/$productSlug' title='$productTitle'>$productTitle</a></td>
                    <td>$title</td>
                    <td>$review</td>
                    <td>$rating</td>
                    <td>
                    <a href=\"check-approve-single-review/$id\" class=\"btn btn-success btn-xs\">Approve</a>
                    <a href=\"check-delete-single-review/$id\" class=\"btn btn-warning btn-xs\">Delete</a>
                    </td>
        </tr>";
    }
}
    