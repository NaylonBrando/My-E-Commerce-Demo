<?php

use admin\controller\ReviewController;

$reviewController = new ReviewController();
?>
<div class="pt-3">
    <h2>Review</h2>
    <form  method="post">
        <button class="btn btn-success btn-sm" type="submit" name="approve" formaction="/admin/check-approve-selected-reviews">Approve selected reviews</button>
        <button class="btn btn-warning btn-sm" type="submit" name="delete" formaction="/admin/check-delete-selected-reviews">Delete selected reviews</button>
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
                <?php $reviewController->reviewRowGenerator() ?>
                </tbody>
            </table>
        </div>
    </form>
</div>