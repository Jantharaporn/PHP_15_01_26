<?php
$host = 'localhost';
$db   = 'it67040233119';
$user = 'it67040233119';
$pass = 'M1Q8L9N6';
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

/* ================= AJAX ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'add') {
        $stmt = $conn->prepare(
            "INSERT INTO users (name, sex, phone, email, birthday)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $_POST['name'], $_POST['sex'],
            $_POST['phone'], $_POST['email'],
            $_POST['birthday']
        ]);

        $id = $conn->lastInsertId();
        echo json_encode(
            $conn->query("SELECT * FROM users WHERE id=$id")->fetch()
        );
        exit;
    }

    if ($_POST['action'] === 'update') {
        $stmt = $conn->prepare(
            "UPDATE users SET name=?, sex=?, phone=?, email=?, birthday=? WHERE id=?"
        );
        $stmt->execute([
            $_POST['name'], $_POST['sex'],
            $_POST['phone'], $_POST['email'],
            $_POST['birthday'], $_POST['id']
        ]);
        echo json_encode($_POST);
        exit;
    }

    if ($_POST['action'] === 'delete') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$_POST['id']]);
        exit;
    }

    if ($_POST['action'] === 'get') {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$_POST['id']]);
        echo json_encode($stmt->fetch());
        exit;
    }
}

$users = $conn->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>CRUD Users PDO</title>

<!-- Bootstrap 5.3.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="bg-light">

<div class="container py-5">
<div class="card shadow-lg border-0 rounded-4">

<div class="card-header bg-primary bg-gradient text-white d-flex justify-content-between align-items-center rounded-top-4">
    <h5 class="mb-0 fw-semibold">จัดการข้อมูลผู้ใช้</h5>
    <button class="btn btn-light btn-sm px-3" onclick="openAdd()" data-bs-toggle="modal" data-bs-target="#userModal">
        + เพิ่มข้อมูล
    </button>
</div>

<div class="card-body p-4">
<table class="table table-hover align-middle mb-0">
<thead class="table-secondary">
<tr>
    <th width="50">#</th>
    <th>ชื่อ</th>
    <th>เพศ</th>
    <th>โทร</th>
    <th>Email</th>
    <th>วันเกิด</th>
    <th width="140">จัดการ</th>
</tr>
</thead>
<tbody id="userTable">
<?php $i=1; foreach ($users as $u): ?>
<tr id="row<?= $u['id'] ?>">
    <td><?= $i++ ?></td>
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
</div>
</div>

<!-- ADD / EDIT MODAL -->
<div class="modal fade" id="userModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content rounded-4 shadow">

<form id="userForm">
<div class="modal-header bg-primary bg-gradient text-white rounded-top-4">
    <h5 class="modal-title" id="modalTitle"></h5>
</div>

<div class="modal-body">
    <input type="hidden" name="id" id="id">
    <input type="hidden" name="action" id="action">

    <input class="form-control mb-2" name="name" id="name" placeholder="ชื่อ" required>
    <select class="form-select mb-2" name="sex" id="sex">
        <option value="ชาย">ชาย</option>
        <option value="หญิง">หญิง</option>
    </select>
    <input class="form-control mb-2" name="phone" id="phone" placeholder="โทร">
    <input class="form-control mb-2" name="email" id="email" placeholder="Email">
    <input type="date" class="form-control" name="birthday" id="birthday">
</div>

<div class="modal-footer">
    <button class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
    <button class="btn btn-success">บันทึก</button>
</div>
</form>

</div>
</div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content rounded-4 shadow">

<div class="modal-header bg-danger bg-gradient text-white rounded-top-4">
    <h5 class="modal-title">ยืนยันการลบข้อมูล</h5>
</div>

<div class="modal-body">
<ul class="list-group list-group-flush">
    <li class="list-group-item"><b>ชื่อ:</b> <span id="d_name"></span></li>
    <li class="list-group-item"><b>เพศ:</b> <span id="d_sex"></span></li>
    <li class="list-group-item"><b>โทร:</b> <span id="d_phone"></span></li>
    <li class="list-group-item"><b>Email:</b> <span id="d_email"></span></li>
    <li class="list-group-item"><b>วันเกิด:</b> <span id="d_birthday"></span></li>
</ul>
<p class="text-danger fw-semibold mt-3 mb-0">
    ⚠️ ลบแล้วไม่สามารถกู้คืนได้
</p>
</div>

<div class="modal-footer">
    <button class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
    <button class="btn btn-danger" onclick="confirmDelete()">ยืนยันลบ</button>
</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
let deleteId = 0;
let rowCount = $('#userTable tr').length;
let userModal = new bootstrap.Modal(document.getElementById('userModal'));
let deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

function openAdd(){
    $('#userForm')[0].reset();
    $('#action').val('add');
    $('#modalTitle').text('เพิ่มข้อมูล');
}

function openEdit(id){
    $.post('', {action:'get', id}, res => {
        let u = JSON.parse(res);
        $('#id').val(u.id);
        $('#name').val(u.name);
        $('#sex').val(u.sex);
        $('#phone').val(u.phone);
        $('#email').val(u.email);
        $('#birthday').val(u.birthday);
        $('#action').val('update');
        $('#modalTitle').text('แก้ไขข้อมูล');
        userModal.show();
    });
}

$('#userForm').submit(function(e){
    e.preventDefault();
    $.post('', $(this).serialize(), res => {
        let u = JSON.parse(res);

        if ($('#action').val() === 'add') {
            rowCount++;
            $('#userTable').append(`
            <tr id="row${u.id}">
                <td>${rowCount}</td>
                <td>${u.name}</td>
                <td>${u.sex}</td>
                <td>${u.phone}</td>
                <td>${u.email}</td>
                <td>${u.birthday}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="openEdit(${u.id})">แก้ไข</button>
                    <button class="btn btn-danger btn-sm" onclick="openDelete(${u.id})">ลบ</button>
                </td>
            </tr>`);
        } else {
            let r = $('#row'+u.id);
            r.children().eq(1).text(u.name);
            r.children().eq(2).text(u.sex);
            r.children().eq(3).text(u.phone);
            r.children().eq(4).text(u.email);
            r.children().eq(5).text(u.birthday);
        }
        userModal.hide();
    });
}

function openDelete(id){
    deleteId = id;
    let r = $('#row'+id).children();
    $('#d_name').text(r.eq(1).text());
    $('#d_sex').text(r.eq(2).text());
    $('#d_phone').text(r.eq(3).text());
    $('#d_email').text(r.eq(4).text());
    $('#d_birthday').text(r.eq(5).text());
    deleteModal.show();
}

function confirmDelete(){
    $.post('', {action:'delete', id:deleteId}, () => {
        $('#row'+deleteId).remove();
        deleteModal.hide();
    });
}
</script>
</body>
</html>
