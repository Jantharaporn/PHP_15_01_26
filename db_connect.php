<?php
    $host = 'localhost';          //ชื่อโฮสต์
    $db = 'root';                 //ชื่อผู้ใช้ฐานข้อมูล
    $pass = '';                   //รหัสผ่านฐานข้อมูล
    $dbname = 'database_it67';    //ชื่อฐานข้อมูล

    $conn =new mysqli($host, $db, $pass, $dbname);
    mysqli_set_charset($conn, "utf8");

    if(!$conn){
        die("เชื่อมต่อไม่สำเร็จ" . mysqli_connect_error());
    }
?>