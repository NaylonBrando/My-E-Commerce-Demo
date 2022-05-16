<?php

use admin\controller\BrandController;
use src\entity\Brand;

$brandController = new BrandController();

function brandTableRow($id, $brandName): string
{
    return "
        <tr>
            <td>$id</td>
            <td>$brandName</td>
            <td><a class=\"btn btn-info btn-sm\" href=\"brand/update/$id\" role=\"button\">Update</a></td>
            <td><a class=\"btn btn-danger btn-sm\" href=\"check-delete-brand/$id\" role=\"button\" onclick=\"return confirm('Are you sure delete $brandName ?');\">Delete</a></td>
        </tr>
        ";
}

/**
 * @param Brand[] $brands
 */
function brandTableRowGenerator(array $brands)
{
    $str = "";
    foreach ($brands as $row) {
        $str .= brandTableRow($row->getId(), $row->getName());
    }
    echo $str;
}

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
            <?php if (isset($brands)) {
                brandTableRowGenerator($brands);
            } else {
                echo "<tr><td colspan='4'>No brands found</td></tr>";
            } ?>
            </tbody>
        </table>
    </div>
</div>
