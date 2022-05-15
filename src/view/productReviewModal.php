<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Write Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/check-add-review-to-product" method="post">
                    <input type="hidden" name="productId" value="<?php echo $product->getId(); ?>">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Title</label>
                        <input type="text" class="form-control" name="title" id="exampleFormControlTextarea1"
                               placeholder="Title" required>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Write your review</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" required
                                      name="review" maxlength="254"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Rating</label>
                            <select class="form-control" id="exampleFormControlSelect1" name="rating" required>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>