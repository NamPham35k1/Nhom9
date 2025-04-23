<?php
// Kết nối đến cơ sở dữ liệu
include '../connect.php'; // Đảm bảo đường dẫn đúng đến tệp kết nối

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sdt = $_POST['sdt'];
    $address = $_POST['address'];
    $rule = $_POST['rule'];

    // Kiểm tra nếu số điện thoại đã tồn tại
    $sql_check = "SELECT * FROM user WHERE sdt = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $sdt);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<div class='alert alert-warning'>Số điện thoại đã tồn tại, vui lòng nhập số khác!</div>";
    } else {
        // Nếu không trùng, tiến hành thêm người dùng
        $sql = "INSERT INTO user (name, email, password, sdt, address, rule) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $password, $sdt, $address, $rule);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Thêm người dùng thành công!</div>";
            if (isset($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                header("Location: ../index.php?page=expenses");
            }
        } else {
            echo "<div class='alert alert-danger'>Lỗi khi thêm người dùng: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }

    $stmt_check->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Thêm Người Dùng Mới</h3>

        <!-- Form để thêm người dùng -->
        <form method="POST" action="add_user.php">
            <div class="mb-3">
                <label for="name" class="form-label">Tên Người Dùng</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="sdt" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="sdt" name="sdt" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="rule" class="form-label">Quyền</label>
                <select class="form-control" id="rule" name="rule" required>
                    <option value="Quản lý">Quản lý</option>
                    <option value="Nhân viên">Nhân viên</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Thêm Người Dùng</button>
            <a href="../index.php?page=user" class="btn btn-secondary">Quay Lại</a>
        </form>
    </div>

    <!-- Link đến Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
