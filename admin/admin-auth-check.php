<?php
include('../dbcon.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location: admin-login.php");
} else {
    $id = $_SESSION['admin_id'];
    $result = mysqli_query($con, "select * from admins where id='$id'") or die('Error In Session');
    $adminRow = mysqli_fetch_array($result);
}
?>