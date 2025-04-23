<?php
include '../connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT id, donvi, address, sdt, diem FROM supplier WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Không tìm thấy nhà cung cấp.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $donvi = $_POST['donvi'];
        $address = $_POST['address'];
        $sdt = $_POST['sdt'];
        $diem = $_POST['diem'];

        // Kiểm tra số điện thoại chỉ được phép là 10 chữ số
        if (!preg_match('/^[0-9]{10}$/', $sdt)) {
            echo "<script>
                alert('Số điện thoại không hợp lệ! Vui lòng nhập đúng 10 chữ số.');
                window.history.back();
            </script>";
            exit;
        }

        // Kiểm tra trùng lặp số điện thoại trong cơ sở dữ liệu (trừ chính bản ghi đang chỉnh sửa)
        $sql_check = "SELECT id FROM supplier WHERE sdt = ? AND id != ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("si", $sdt, $id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            echo "<script>
                alert('Số điện thoại đã tồn tại! Vui lòng nhập số điện thoại khác.');
                window.history.back();
            </script>";
        } else {
            // Cập nhật dữ liệu nhà cung cấp
            $update_sql = "UPDATE supplier SET donvi = ?, address = ?, sdt = ?, diem = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssi", $donvi, $address, $sdt, $diem, $id);

            if ($update_stmt->execute()) {
                echo "<script>
                    alert('Cập nhật nhà cung cấp thành công!');
                    window.location.href = '../index.php?page=suppliers';
                </script>";
            } else {
                echo "<script>
                    alert('Lỗi khi cập nhật dữ liệu.');
                    window.history.back();
                </script>";
            }

            $update_stmt->close();
        }

        $stmt_check->close();
    }

} else {
    echo "ID không hợp lệ.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thông Tin Nhà Cung Cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Sửa Thông Tin Nhà Cung Cấp</h3>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="donvi" class="form-label">Đơn vị cung cấp</label>
                <input type="text" class="form-control" id="donvi" name="donvi" value="<?php echo $row['donvi']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo $row['address']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="sdt" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="sdt" name="sdt" 
                       value="<?php echo $row['sdt']; ?>" pattern="[0-9]{10}" 
                       title="Vui lòng nhập đúng 10 chữ số" required>
            </div>
            <div class="mb-3">
                <label for="diem" class="form-label">Điểm</label>
                <input type="number" class="form-control" id="diem" name="diem" value="<?php echo $row['diem']; ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Cập Nhật</button>
            <a href="../index.php?page=suppliers" class="btn btn-primary">Quay Lại</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
