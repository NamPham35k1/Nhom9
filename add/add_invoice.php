<?php
include '../connect.php';  // Kết nối cơ sở dữ liệu

// Truy vấn danh sách nhà cung cấp
$sql = "SELECT id, donvi FROM supplier";
$result = $conn->query($sql);

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $id_supplier = $_POST['id_supplier'];
    $total = $_POST['total'];
    $date = $_POST['date'];

    // Kiểm tra nếu các trường không rỗng
    if (!empty($id_supplier) && !empty($total) && !empty($date)) {
        // Chèn thông tin hóa đơn vào bảng bill
        $sql = "INSERT INTO bill (id_supplier, total, date) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ids', $id_supplier, $total, $date);  // 'i' cho INT, 'd' cho DECIMAL, 's' cho DATE

        if ($stmt->execute()) {
            // Sau khi thành công, chuyển hướng đến trang xem chi tiết hóa đơn
            $invoice_id = $stmt->insert_id;
            echo "<div class='alert alert-success'>Hóa đơn đã được tạo thành công! <a href='detail_invoice.php?id=" . $invoice_id . "'>Xem chi tiết</a></div>";
        } else {
            echo "<div class='alert alert-danger'>Lỗi khi tạo hóa đơn: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-warning'>Vui lòng điền đầy đủ thông tin!</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Hóa Đơn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Tạo Hóa Đơn</h3>

        <!-- Form tạo hóa đơn -->
        <form method="POST" action="add_invoice.php">
            <div class="form-group">
                <label for="supplier">Chọn nhà cung cấp</label>
                <select name="id_supplier" id="supplier" class="form-control" required>
                    <option value="">Chọn nhà cung cấp</option>
                    <?php
                    // Hiển thị các nhà cung cấp
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['donvi'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="total">Tổng tiền</label>
                <input type="number" name="total" class="form-control" id="total" required>
            </div>

            <div class="form-group">
                <label for="date">Ngày tạo hóa đơn</label>
                <input type="date" name="date" class="form-control" id="date" required>
            </div>

            <div class="d-flex mt-3">
                    <button type="submit" class="btn btn-primary">Tạo hóa đơn</button>
                    <a href="../index.php?page=invoice" class="btn btn-secondary" style="margin-left:20px;">Quay lại</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
