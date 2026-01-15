<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show User</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6f8;
            margin: 0;
            padding: 40px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .table-container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 14px;
            text-align: left;
        }
        th {
            background-color: #f0f2f5;
            color: #333;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }
        td {
            border-bottom: 1px solid #eee;
            color: #555;
        }
        tr:hover {
            background-color: #f9fafb;
        }
        .no-data {
            text-align: center;
            color: #999;
            padding: 20px;
        }
    </style>
</head>

<body>

<h1>แสดงข้อมูลจากฐานข้อมูล ตาราง Users</h1>

<div class="table-container">
<?php
include 'db_connect_pdo.php';

$sql = "SELECT * FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll();

if (count($rows) > 0) {
    echo "<table>";
    echo "<tr>
            <th>รหัส</th>
            <th>ชื่อสกุล</th>
            <th>เพศ</th>
            <th>โทรศัพท์</th>
            <th>อีเมล์</th>
            <th>วันเกิด</th>
          </tr>";

    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['sex']}</td>";
        echo "<td>{$row['phone']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['birthday']}</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<div class='no-data'>ไม่พบข้อมูล</div>";
}

$conn = null; // ปิดการเชื่อมต่อ PDO
?>
</div>

</body>
</html>
