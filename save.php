<?php
include 'db_connect.php';

$id = $_POST['id'];
$name = $_POST['name'];
$sex = $_POST['sex'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$birthday = $_POST['birthday'];

if($id == ""){
    mysqli_query($conn,"INSERT INTO users
    (name,sex,phone,email,birthday)
    VALUES('$name','$sex','$phone','$email','$birthday')");
}else{
    mysqli_query($conn,"UPDATE users SET
    name='$name',
    sex='$sex',
    phone='$phone',
    email='$email',
    birthday='$birthday'
    WHERE id=$id");
}

header("Location: crud_users.php");
