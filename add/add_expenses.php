<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $price = floatval($_POST['price']);
    $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
    $quantity = intval($_POST['quantity']);
    $status = trim($_POST['status']);

    if ($_FILES['image']['error'] == 0) {
        // Tạo tên ảnh mới với phần mở rộng
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "../image/";
        $target_file = $target_dir . $image_name;
        
        // Kiểm tra xem có thể di chuyển tệp không
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Thực hiện query SQL để lưu vào cơ sở dữ liệu
            $sql = "INSERT INTO inventory (name, type, price, discount, quantity, status,image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdiiss", $name, $type, $price, $discount, $quantity, $status,$image_name);
    
            if ($stmt->execute()) {
                echo "<div style='color: green;'>Sản phẩm đã được thêm thành công!</div>";
            } else {
                echo "<div style='color: red;'>Lỗi khi thêm sản phẩm vào cơ sở dữ liệu: " . $stmt->error . "</div>";
            }
            $stmt->close();
        } else {
            echo "<div style='color: red;'>Lỗi khi di chuyển file ảnh. Vui lòng kiểm tra quyền truy cập thư mục!</div>";
        }
    } else {
        echo "<div style='color: red;'>Lỗi khi tải lên ảnh: " . $_FILES['image']['error'] . "</div>";
    }
    
    
    
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Thêm Sản Phẩm Mới</h3>
        <form action="add_expenses.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Loại sản phẩm</label>
                <input type="text" class="form-control" id="type" name="type" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Giá tiền</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>

            <div class="mb-3">
                <label for="discount" class="form-label">Giảm giá</label>
                <input type="number" class="form-control" id="discount" name="discount">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Ảnh sản phẩm</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Số lượng</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="Available">Còn hàng</option>
                    <option value="Unavailable">Hết hàng</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Thêm sản phẩm</button>
            <a href="../index.php?page=expenses" class="btn btn-secondary">Quay Lại</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
