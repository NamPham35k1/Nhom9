<?php
include '../connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    echo "ID không hợp lệ.";
    exit;
}

// Lấy thông tin khách hàng theo ID
$sql = "SELECT * FROM client WHERE id_kh = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Không tìm thấy khách hàng.";
    exit;
}

$customer = $result->fetch_assoc();

// Cập nhật thông tin khách hàng khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = $_POST['name'];
    $ngay_sinh = $_POST['ns'];
    $gioi_tinh = $_POST['gt'];
    $sdt = $_POST['sdt'];
    $dia_chi = $_POST['address'];

    $update_sql = "UPDATE client SET name = ?, ns = ?, gt = ?, address = ?,sdt = ? WHERE id_kh = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssi", $ten, $ngay_sinh, $gioi_tinh, $dia_chi, $sdt, $id);

    if ($update_stmt->execute()) {
        echo "Cập nhật thành công!";
        header("Location: ../index.php?page=management");
        exit;
    } else {
        echo "Lỗi khi cập nhật: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Khách Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Cập Nhật Thông Tin Khách Hàng</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="ten" class="form-label">Tên khách hàng</label>
            <input type="text" name="name" id="ten" class="form-control" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="ngay_sinh" class="form-label">Ngày sinh</label>
            <input type="date" name="ns" id="ngay_sinh" class="form-control" value="<?php echo $customer['ns']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Giới tính</label>
            <div>
                <input type="radio" name="gt" value="Nam" <?php echo ($customer['gt'] === 'Nam') ? 'checked' : ''; ?>> Nam
                <input type="radio" name="gt" value="Nữ" <?php echo ($customer['gt'] === 'Nữ') ? 'checked' : ''; ?>> Nữ
            </div>
        </div>
        <div class="mb-3">
            <label for="sdt" class="form-label">Số điện thoại</label>
            <input type="text" name="sdt" id="sdt" class="form-control" value="<?php echo htmlspecialchars($customer['sdt']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="dia_chi" class="form-label">Địa chỉ</label>
            <textarea name="address" id="dia_chi" class="form-control" required><?php echo htmlspecialchars($customer['address']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
        <a href="../index.php?page=management" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
