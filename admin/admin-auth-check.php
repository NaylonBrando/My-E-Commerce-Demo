<?php
include('../dbcon.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location: admin-login.php");
} else {
    $id = $_SESSION['admin_id'];
}
?>