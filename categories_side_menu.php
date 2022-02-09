<?php
include("dbcon.php");
include('category_dal.php');
?>
<?php


function listAllCategories($parentId = 0)
{
    global $con;
    $result = mysqli_query($con, "SELECT * FROM categories WHERE parent_id = '$parentId'");


    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            $id = $row['id'];
            echo '<li><a href="mainpage.php?catId=' .$id. '&pg=1' . '">' . $row['category_name'] . '</a> <ul>';
            listAllCategories($row['id']);
            echo '</ul> </li>';
        }

    }

}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Side Cateogorie Menu</title>
    <link rel="stylesheet" href="categories_side_menu.css">
</head>
<body>

<div class="vertical">
    <ul>
        <li><a href="mainpage.php">Tümü</a></li>
        <?php listAllCategories(); ?>

    </ul>
</div>

</body>
</html>

