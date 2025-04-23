<?php
include '../connect.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $donvi = $_POST['donvi'];
    $sdt = $_POST['sdt'];
    $address = $_POST['address'];
    $diem = $_POST['diem'];

    // Kiểm tra số điện thoại chỉ chứa 10 chữ số
    if (!preg_match('/^[0-9]{10}$/', $sdt)) {
        echo "<script>
            alert('Số điện thoại không hợp lệ! Vui lòng nhập đúng 10 chữ số.');
            window.history.back(); // Quay lại trang trước
        </script>";
        exit;
    }

    // Kiểm tra trùng lặp số điện thoại trong cơ sở dữ liệu
    $sql_check = "SELECT * FROM supplier WHERE sdt = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $sdt);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Nếu số điện thoại đã tồn tại
        echo "<script>
            alert('Số điện thoại đã tồn tại! Vui lòng nhập số điện thoại khác.');
            window.history.back(); // Quay lại trang trước
        </script>";
    } else {
        // Thêm nhà cung cấp mới vào cơ sở dữ liệu
        $sql = "INSERT INTO supplier (donvi, address, sdt, diem) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $donvi, $address, $sdt, $diem);

        if ($stmt->execute()) {
            echo "<script>
                alert('Thêm nhà cung cấp thành công!');
                window.location.href = '../index.php?page=suppliers'; // Chuyển hướng về danh sách
            </script>";
        } else {
            echo "<script>
                alert('Lỗi khi thêm nhà cung cấp! Vui lòng thử lại.');
                window.history.back();
            </script>";
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
    <title>Thêm Nhà Cung Cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Thêm Nhà Cung Cấp Mới</h3>
        <form method="POST" action="add_supplier.php">
            <div class="mb-3">
                <label for="donvi" class="form-label">Đơn vị</label>
                <input type="text" class="form-control" id="donvi" name="donvi" required>
            </div>
            <div class="mb-3">
                <label for="sdt" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="sdt" name="sdt" 
                       pattern="[0-9]{10}" title="Vui lòng nhập đúng 10 chữ số" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="diem" class="form-label">Điểm</label>
                <input type="text" class="form-control" id="diem" name="diem" required>
            </div>
            <button type="submit" class="btn btn-success">Thêm Nhà Cung Cấp</button>
            <a href="../index.php?page=suppliers" class="btn btn-primary">Quay Lại</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
