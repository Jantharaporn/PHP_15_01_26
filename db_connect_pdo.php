<?php
$host = 'localhost';
$dbname = 'it67040233119';
$user = 'it67040233119';
$pass = 'M1Q8L9N6';
$charset = 'utf8';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ: " . $e->getMessage());
}
