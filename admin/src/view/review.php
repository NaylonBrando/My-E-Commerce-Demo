<?php

use admin\controller\ReviewController;
use src\dto\ReviewWithUserDto;

$reviewController = new ReviewController();

/**
 * @param ReviewWithUserDto[] $reviews
 * @return void
 */
function reviewRowGenerator(array $reviews)
{
    $str = "";
    foreach ($reviews as $review) {
        $str .= reviewRow($review->getReview()->getId(), $review->getUserName(), $review->getUserLastName(),
            $review->getUserEmail(), $review->getReview()->getCreatedAt()->format('d/m/Y H:i:s'), $review->getReview()->getTitle(),
            $review->getReview()->getReview(), $review->getReview()->getRating(), $review->getProductTitle(), $review->getProductSlug());
    }
    echo $str;
}

function reviewRow($id, $name, $lastName, $email, $date, $title, $review, $rating, $productTitle, $productSlug): string
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

?>
<div class="pt-3">
    <h2>Review</h2>
    <form method="post">
        <button class="btn btn-success btn-sm" type="submit" name="approve"
                formaction="/admin/check-approve-selected-reviews">Approve selected reviews
        </button>
        <button class="btn btn-warning btn-sm" type="submit" name="delete"
                formaction="/admin/check-delete-selected-reviews">Delete selected reviews
        </button>
        <div class="table-responsive mt-2">
            <table class="table table-striped table-sm text-center">
                <thead class="thead-light">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Customer</th>
                    <th scope="col">Date</th>
                    <th scope="col">Product</th>
                    <th scope="col">Title</th>
                    <th scope="col">Review</th>
                    <th scope="col">Rating</th>
                    <th scope="col">Button</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($reviews)) {
                    reviewRowGenerator($reviews);
                }
                else{
                    echo "<tr><td colspan='8'>No reviews</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </form>
</div>