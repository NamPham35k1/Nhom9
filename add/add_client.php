<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten = isset($_POST['name']) ? $_POST['name'] : '';
    $ngay_sinh = isset($_POST['ns']) ? $_POST['ns'] : '';
    $gioi_tinh = isset($_POST['gt']) ? $_POST['gt'] : '';
    $sdt = isset($_POST['sdt']) ? $_POST['sdt'] : '';
    $dia_chi = isset($_POST['address']) ? $_POST['address'] : '';

    if (!empty($ten) && !empty($ngay_sinh) && !empty($gioi_tinh) && !empty($sdt) && !empty($dia_chi)) {
        // Kiểm tra xem số điện thoại đã tồn tại hay chưa
        $sql_check = "SELECT * FROM client WHERE sdt = ?";
        $stmt_check = $conn->prepare($sql_check);

        if ($stmt_check) {
            $stmt_check->bind_param("s", $sdt);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                echo "<div class='alert alert-warning'>Số điện thoại đã tồn tại, vui lòng nhập số khác!</div>";
            } else {
                // Chèn dữ liệu mới vào bảng client
                $sql = "INSERT INTO client (name, ns, gt, address, sdt) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("sssss", $ten, $ngay_sinh, $gioi_tinh, $dia_chi, $sdt);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Thêm khách hàng thành công!</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Lỗi: " . $stmt->error . "</div>";
                    }

                    $stmt->close();
                } else {
                    echo "<div class='alert alert-danger'>Lỗi chuẩn bị truy vấn: " . $conn->error . "</div>";
                }
            }

            $stmt_check->close();
        } else {
            echo "<div class='alert alert-danger'>Lỗi kiểm tra số điện thoại: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Vui lòng điền đầy đủ thông tin!</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Khách Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Thêm Khách Hàng Mới</h3>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="ten" class="form-label">Tên khách hàng</label>
                <input type="text" class="form-control" id="ten" name="name" placeholder="Nhập tên khách hàng">
            </div>
            <div class="mb-3">
                <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                <input type="date" class="form-control" id="ngay_sinh" name="ns">
            </div>
            <div class="mb-3">
                <label for="gioi_tinh" class="form-label">Giới tính</label>
                <select class="form-control" id="gioi_tinh" name="gt">
                    <option value="Nam">Nam</option>
                    <option value="Nu">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="sdt" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="sdt" name="sdt" placeholder="Nhập số điện thoại">
            </div>
            <div class="mb-3">
                <label for="dia_chi" class="form-label">Địa chỉ</label>
                <textarea class="form-control" id="dia_chi" name="address" rows="3" placeholder="Nhập địa chỉ"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Khách Hàng</button>
            <a href="../index.php?page=management" class="btn btn-secondary">Quay Lại</a>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
