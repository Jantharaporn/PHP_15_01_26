<?php
include 'db_connect.php';

/* -------- ดึงข้อมูลมาแก้ไข -------- */
$data = ['id'=>'','name'=>'','sex'=>'','phone'=>'','email'=>'','birthday'=>''];

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $q = mysqli_query($conn,"SELECT * FROM users WHERE id=$id");
    $data = mysqli_fetch_assoc($q);
}

/* -------- แสดงข้อมูลทั้งหมด -------- */
$result = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>CRUD Users</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    background:#f5f6fa;
}
.card{
    border:none;
    border-radius:12px;
}
.table th, .table td{
    vertical-align:middle;
}
</style>
</head>

<body>

<div class="container py-4">

<h3 class="mb-4 fw-bold">
<i class="bi bi-people-fill text-primary"></i>
ระบบจัดการข้อมูลผู้ใช้
</h3>

<!-- ฟอร์มเพิ่ม / แก้ไข -->
<div class="card shadow-sm mb-4">
<div class="card-header bg-primary text-white fw-bold">
<?= $data['id'] ? '✏️ แก้ไขข้อมูลผู้ใช้' : '➕ เพิ่มข้อมูลผู้ใช้' ?>
</div>

<div class="card-body">
<form method="post" action="save.php">
<input type="hidden" name="id" value="<?= $data['id'] ?>">



<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">ชื่อ</label>
        <input type="text" name="name" class="form-control" required value="<?= $data['name'] ?>">
    </div>

    <div class="col-md-6">
    <label class="form-label d-block">เพศ</label>

    <div class="form-check form-check-inline">
        <input class="form-check-input"
               type="radio"
               name="sex"
               id="sex_male"
               value="ชาย"
               <?= $data['sex']=='ชาย'?'checked':'' ?>>
        <label class="form-check-label" for="sex_male">
            ชาย
        </label>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input"
               type="radio"
               name="sex"
               id="sex_female"
               value="หญิง"
               <?= $data['sex']=='หญิง'?'checked':'' ?>>
        <label class="form-check-label" for="sex_female">
            หญิง
        </label>
    </div>
</div>


    <div class="col-md-6">
        <label class="form-label">โทรศัพท์</label>
        <input type="text" name="phone" class="form-control" value="<?= $data['phone'] ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">วันเกิด</label>
        <input type="date" name="birthday" class="form-control" value="<?= $data['birthday'] ?>">
    </div>
</div>

<div class="mt-4">
<button type="submit" class="btn btn-success">
<i class="bi bi-save"></i> บันทึก
</button>
<a href="crud_users.php" class="btn btn-secondary">
<i class="bi bi-x-circle"></i> ยกเลิก
</a>
</div>

</form>
</div>
</div>

<!-- ตารางแสดงข้อมูล -->
<div class="card shadow-sm">
<div class="card-header bg-dark text-white fw-bold">
📋 รายชื่อผู้ใช้ทั้งหมด
</div>

<div class="card-body p-0">
<table class="table table-striped table-hover mb-0">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>ชื่อ</th>
<th>เพศ</th>
<th>โทร</th>
<th>Email</th>
<th>วันเกิด</th>
<th class="text-center">จัดการ</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['sex'] ?></td>
<td><?= $row['phone'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['birthday'] ?></td>
<td class="text-center">

<a href="crud_users.php?edit&id=<?= $row['id'] ?>" 
   class="btn btn-warning btn-sm">
<i class="bi bi-pencil-square"></i>
</a>

<button class="btn btn-danger btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#deleteModal"
        data-id="<?= $row['id'] ?>"
        data-name="<?= $row['name'] ?>"
        data-sex="<?= $row['sex'] ?>"
        data-phone="<?= $row['phone'] ?>"
        data-email="<?= $row['email'] ?>"
        data-birthday="<?= $row['birthday'] ?>">
<i class="bi bi-trash"></i>
</button>

</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>

</div>

<!-- Modal ยืนยันการลบ -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form method="post" action="delete.php">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">
          <i class="bi bi-exclamation-triangle-fill"></i>
          ยืนยันการลบข้อมูล
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id" id="delete_id">

          <ul class="list-group">
            <li class="list-group-item"><b>ชื่อ:</b> <span id="d_name"></span></li>
            <li class="list-group-item"><b>เพศ:</b> <span id="d_sex"></span></li>
            <li class="list-group-item"><b>โทรศัพท์:</b> <span id="d_phone"></span></li>
            <li class="list-group-item"><b>Email:</b> <span id="d_email"></span></li>
            <li class="list-group-item"><b>วันเกิด:</b> <span id="d_birthday"></span></li>
          </ul>

          <p class="text-danger mt-3 fw-bold">
            ⚠️ คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้
          </p>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">
          <i class="bi bi-trash"></i> ยืนยันการลบ
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          ยกเลิก
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
var deleteModal = document.getElementById('deleteModal');
deleteModal.addEventListener('show.bs.modal', function (event) {
    var b = event.relatedTarget;

    delete_id.value = b.getAttribute('data-id');
    d_name.innerText = b.getAttribute('data-name');
    d_sex.innerText = b.getAttribute('data-sex');
    d_phone.innerText = b.getAttribute('data-phone');
    d_email.innerText = b.getAttribute('data-email');
    d_birthday.innerText = b.getAttribute('data-birthday');
});
</script>

</body>
</html>
