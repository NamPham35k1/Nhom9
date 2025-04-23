<?php

include '../connect.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';

if (!$id) {
    echo "ID không hợp lệ!";
    exit();
}

$sql = "SELECT id, name, email, password, sdt, address, rule FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Người dùng không tồn tại!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sdt = $_POST['sdt'];
    $address = $_POST['address'];
    $rule = $_POST['rule'];

  
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $hashed_password = $user['password']; 
    }

    $updateSql = "UPDATE user SET name = ?, email = ?, password = ?, sdt = ?, address = ?, rule = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssssssi", $name, $email, $hashed_password, $sdt, $address, $rule, $id);

    if ($updateStmt->execute()) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href = '../index.php?page=user';</script>";
    } else {
        echo "Lỗi cập nhật thông tin: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Sửa Thông Tin Người Dùng</h3>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Tên Người Dùng</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu (để trống nếu không thay đổi)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="sdt" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="sdt" name="sdt" value="<?php echo htmlspecialchars($user['sdt']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="rule" class="form-label">Quyền</label>
                <select class="form-control" id="rule" name="rule" required>
                    <option value="admin" <?php echo ($user['rule'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="user" <?php echo ($user['rule'] == 'user') ? 'selected' : ''; ?>>User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Cập Nhật</button>
            <a href="../index.php?page=user" class="btn btn-secondary">Quay Lại</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
