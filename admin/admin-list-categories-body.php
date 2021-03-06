<?php
if (!isset($_SESSION['admin_id'])) {
    header("location: admin-login.php");
}
include("../dbcon.php");
include('../category_dal.php');
?>

<?php
function component($id, $categoryName)
{
    return "<option value=\"$id\">$categoryName</option>";
}

function components()
{
    $categoryDal = new CategoryDal();
    $result = $categoryDal->GetAllCategories();

    if ($result != null) {
        $str = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $str .= component($row["id"], $row["category_name"]);
        }
        echo $str;
    }
}
$str = "";
function listAllCategories($parentId = 0)
{
    global $con, $str;
    $result = mysqli_query($con, "SELECT * FROM categories WHERE parent_id = '$parentId'");

    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {

            $str.= "<li>" . $row['category_name'] . "<ul> ";
            $str.=listAllCategories($row['id']);
            $str.= "</ul> </li>";
        }
    }
    if ($parentId==0){
        echo $str;
    }
}
?>

<div class="container mt-2 mb-5 ">
    <h3 class="text-center">Kategori ve Alt Kategori İşlemleri</h3>
    <div class="row">
        <div class="col-md-6">
            <h4 class="text-center">Kategori Ekleme</h4>
            <hr>
            <form action="category-crud-operations.php" method="post">
                <div class="form-group">
                    <label for="category-name">Kategori Adı</label>
                    <input type="text" class="form-control" name="category_name" id="category-name" maxlength="80"
                           required>
                </div>

                <div class="form-group">
                    <label for="parent-category-name">Varsa Üst Kategori</label>
                    <select name="parent_id" id="parent-category-name" class="form-control">
                        <option selected value=0>Yok</option>
                        <?php components(); ?>
                    </select>
                </div>

                <div class="form-group mt-2">
                    <button type="submit" name="add-category" class="btn btn-primary btn-sm">Ekle</button>
                    <button type="reset" class="btn btn-danger btn-sm">İptal</button>
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

            <h4 class="text-center">Kategori Güncelleme</h4>
            <hr>
            <form action="admin-update-category.php" method="get">
                <div class="form-group">
                    <label>Kategori Seç</label>
                    <select name="catId" id="category" class="form-control">
                        <?php components(); ?>
                    </select>
                </div>
                <div class="form-group mt-2">
                    <button class="btn btn-warning" type="submit">Güncelleye Git</button>
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
</div>
