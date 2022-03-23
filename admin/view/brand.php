<?php

use admin\controller\BrandController;

$brandController = new BrandController()
?>

<div class="pt-3">
    <h2>Brand</h2>
    <a class="btn btn-primary" href="brand/add" role="button">Add Brand</a>
    <div class="table-responsive mt-2">
        <table class="table table-striped table-sm text-center">
            <thead class="thead-light">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Brand Name</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php $brandController->brandTableRowGenerator() ?>
            </tbody>
        </table>
    </div>
</div>
