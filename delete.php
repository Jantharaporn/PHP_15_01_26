<?php
include 'db_connect.php';

$id = $_POST['id'];
mysqli_query($conn,"DELETE FROM users WHERE id=$id");

header("location:crud_users.php");
