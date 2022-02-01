<?php
if (!isset($_SESSION['admin_id'])) {
    header("location: admin-login.php");
}
include("../dbcon.php");
include('../category_dal.php');
?>

<?php

$con = mysqli_connect("localhost", "root", "", "ecommerce");

function component($id, $categoryName)
{
    $element = "
    <option selected value=\"$id\">$categoryName</option>
    ";

    return $element;
}

function components()
{
    $categoryDal = new CategoryDal();
    $result = $categoryDal->GetAllCategories();

    if ($result != null) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo component($row["id"], $row["category_name"]);

        }
    }

}

function listAllCategories($id = 0)
{
    global $con;
    $result = mysqli_query($con, "SELECT * FROM categories WHERE parent_id = '$id'");


    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {


            if ($id == 0) {
                echo '<li>' . $row['category_name'] . '</li>';
                listAllCategories($row['id']);
            } else {
                echo '<ul>';
                echo '<li>' . $row['category_name'] . '</li>';
                listAllCategories($row['id']);
                echo '</ul>';
            }

        }

    }

}

?>


<div class="container pt-2">
    <h3 class="text-center">Kategori ve Alt Kategori İşlemleri</h3>
    <div class="row">
        <div class="col-md-6">
            <h4 class="text-center">Kategori Ekleme</h4>
            <hr>
            <form action="category-crud-operations.php" method="post">
                <div class="form-group">
                    <label for="category-name">Kategori Adı</label>
                    <input type="text" class="form-control" name="category_name" id="category-name" required>
                </div>

                <div class="form-group">
                    <label for="parent-category-name">Varsa Üst Kategori</label>
                    <select name="parent_id" id="parent-category-name" class="form-control">
                        <option selected value=\"0\">Yok</option>
                        <?php components(); ?>
                    </select>
                </div>

                <div class="form-group mt-2">
                    <button type="submit" name="add-category" class="btn btn-primary btn-sm">Ekle</button>
                    <button type="submit" class="btn btn-danger btn-sm">İptal</button>
                </div>
            </form>
            <br>

            <h4 class="text-center">Kategori Silme</h4>
            <hr>

            <form action="category-crud-operations.php" method="post">
                <div class="form-group">
                    <label for="parent-category-name2">Kategori İsmi</label>
                    <select name="delete-category-id" id="parent-category-name2" class="form-control">
                        <?php components(); ?>
                    </select>
                </div>
                <div class="form-group mt-2">
                    <button type="submit" name="delete-category" class="btn btn-danger btn-sm">Sil</button>
                </div>
            </form>

        </div>
        <div class="col-md-6">
            <h4 class="text-center">Kategori Hiyerarşisi</h4>
            <hr>
            <ul>
                <?php listAllCategories(); ?>
            </ul>

        </div>
    </div>
    <?php ?>
</div>
