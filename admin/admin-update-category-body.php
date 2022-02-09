<?php
if (!isset($_SESSION['admin_id'])) {
    header("location: admin-login.php");
}
include("../dbcon.php");
include("../category_dal.php");

?>

<?php
if (isset($_GET['catId'])) {

    $categoryDal = new CategoryDal();
    $resultCategory = $categoryDal->GetCategoryById($_GET['catId']);
    $row = mysqli_fetch_assoc($resultCategory);
    $categoryId = $row["id"];
    $categoryName = $row["category_name"];
    $parentId = $row["parent_id"];

    function CategoryComponent($id, $categoryName)
    {
        return "<option name='category_id' value=\"$id\">$categoryName</option>";;
    }

    function CategoryComponentParent($id, $categoryName)
    {
        return "<option selected name='category_id' value=\"$id\">$categoryName</option>";
    }


    function CategoryComponents()
    {
        global $categoryId, $parentId;
        $categoryDal = new CategoryDal();
        $result = $categoryDal->GetAllCategories();

        if ($result != null) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($categoryId != $row['id']) {
                    if ($parentId == $row['id']) {
                        echo CategoryComponentParent($row["id"], $row["category_name"]);
                    } else {
                        echo CategoryComponent($row["id"], $row["category_name"]);
                    }
                }
            }
        }

    }

} else {
    echo '<h2>Seçili Ürün Yok </h2>';
    header("refresh:0.5;url=admin-list-products.php");
}

?>

<div class="container pt-3">
    <section class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Kategori Güncelle</h3>
        </div>
        <hr>
        <div class="panel-body">
            <form action='category-crud-operations.php' method="post">
                <div class="col-sm-9">
                    <input value="<?php echo $categoryId; ?>" type="hidden" class="form-control" name="id">
                </div>
                <label for="category-name" class="col-sm-3 control-label">Mevcut Kategori İsmi</label>
                <div class="col-sm-9">
                    <input value="<?php echo $categoryName; ?>" type="text" class="form-control" name="category_name"
                           id="category-name" maxlength="80" required autofocus>
                </div>
                <label for="parent-category-name" class="col-sm-3 control-label">Üst Kategori</label>
                <div class="col-sm-3">
                    <select class="form-control" name="parent_id" id="parent-category-name">
                        <option selected value=0>Yok</option>
                        <?php CategoryComponents(); ?>
                    </select>
                </div>
                <hr>
                <div class="col-sm-offset-3 col-sm-9 pb-3">
                    <button type="submit" class="btn btn-primary" name="update-category">Güncelle</button>
                </div>
            </form>
        </div>
</div>
