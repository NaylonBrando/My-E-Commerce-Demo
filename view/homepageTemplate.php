<!DOCTYPE html>
<html lang="en">

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/layout/head.php'); ?>
<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/layout/navigationBar.php'); ?>



<?php
if (isset($pageModule)) {
    require_once($pageModule);
}
else{
    echo 'No Page Module';
}?>
</body>
</html>