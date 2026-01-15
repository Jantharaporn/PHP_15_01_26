<?php
/* =======================
   PDO CONNECT
======================= */
$host = 'localhost';
$db   = 'database_it67';
$user = 'root';
$pass = '';
$charset = 'utf8';

$conn = new PDO(
    "mysql:host=$host;dbname=$db;charset=$charset",
    $user,
    $pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

/* =======================
   AJAX ACTION
======================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    /* ADD */
    if ($_POST['action'] === 'add') {
        $stmt = $conn->prepare(
            "INSERT INTO users (name, sex, phone, email, birthday)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $_POST['name'],
            $_POST['sex'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['birthday']
        ]);
        exit;
    }

    /* UPDATE */
    if ($_POST['action'] === 'update') {
        $stmt = $conn->prepare(
            "UPDATE users SET
             name=?, sex=?, phone=?, email=?, birthday=?
             WHERE id=?"
        );
        $stmt->execute([
            $_POST['name'],
            $_POST['sex'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['birthday'],
            $_POST['id']
        ]);
        exit;
    }

    /* DELETE */
    if ($_POST['action'] === 'delete') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$_POST['id']]);
        exit;
    }

    /* GET SINGLE */
    if ($_POST['action'] === 'get') {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$_POST['id']]);
        echo json_encode($stmt->fetch());
        exit;
    }
}

/* =======================
   FETCH DATA
======================= */
$users = $conn->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>CRUD Users PDO</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-3">CRUD Users (PDO + AJAX)</h3>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#userModal"
            onclick="openAdd()">+ เพิ่มข้อมูล</button>

    <table class="table table-bordered table-hover bg-white">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>ชื่อ</th>
                <th>เพศ</th>
                <th>โทร</th>
                <th>Email</th>
                <th>วันเกิด</th>
                <th width="150">จัดการ</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= $u['name'] ?></td>
                <td><?= $u['sex'] ?></td>
                <td><?= $u['phone'] ?></td>
                <td><?= $u['email'] ?></td>
                <td><?= $u['birthday'] ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="openEdit(<?= $u['id'] ?>)">แก้ไข</button>
                    <button class="btn btn-danger btn-sm" onclick="openDelete(<?= $u['id'] ?>)">ลบ</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ADD / EDIT MODAL -->
<div class="modal fade" id="userModal">
<div class="modal-dialog">
<div class="modal-content">
<form id="userForm">
<div class="modal-header">
    <h5 class="modal-title" id="modalTitle"></h5>
</div>
<div class="modal-body">
    <input type="hidden" name="id" id="id">
    <input type="hidden" name="action" id="action">

    <input class="form-control mb-2" name="name" id="name" placeholder="ชื่อ" required>

    <select class="form-control mb-2" name="sex" id="sex">
        <option value="ชาย">ชาย</option>
        <option value="หญิง">หญิง</option>
    </select>

    <input class="form-control mb-2" name="phone" id="phone" placeholder="โทร">
    <input class="form-control mb-2" name="email" id="email" placeholder="Email">
    <input type="date" class="form-control" name="birthday" id="birthday">
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
    <button class="btn btn-success" type="submit">บันทึก</button>
</div>
</form>
</div>
</div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header bg-danger text-white">
    <h5 class="modal-title">ยืนยันการลบ</h5>
</div>
<div class="modal-body">
    <p>คุณต้องการลบข้อมูลนี้หรือไม่?</p>
    <ul>
        <li><b>ชื่อ:</b> <span id="d_name"></span></li>
        <li><b>Email:</b> <span id="d_email"></span></li>
    </ul>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
    <button class="btn btn-danger" onclick="confirmDelete()">ลบ</button>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let deleteId = 0;

function openAdd(){
    $('#userForm')[0].reset();
    $('#action').val('add');
    $('#modalTitle').text('เพิ่มข้อมูล');
}

function openEdit(id){
    $.post('', {action:'get', id:id}, function(res){
        let u = JSON.parse(res);
        $('#id').val(u.id);
        $('#name').val(u.name);
        $('#sex').val(u.sex);
        $('#phone').val(u.phone);
        $('#email').val(u.email);
        $('#birthday').val(u.birthday);
        $('#action').val('update');
        $('#modalTitle').text('แก้ไขข้อมูล');
        new bootstrap.Modal(document.getElementById('userModal')).show();
    });
}

$('#userForm').submit(function(e){
    e.preventDefault();
    $.post('', $(this).serialize(), function(){
        location.reload();
    });
});

function openDelete(id){
    deleteId = id;
    $.post('', {action:'get', id:id}, function(res){
        let u = JSON.parse(res);
        $('#d_name').text(u.name);
        $('#d_email').text(u.email);
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });
}

function confirmDelete(){
    $.post('', {action:'delete', id:deleteId}, function(){
        location.reload();
    });
}
</script>
</body>
</html>
