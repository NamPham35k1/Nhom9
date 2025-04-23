<?php
include 'connect.php';

$search = isset($_POST['search']) ? trim($_POST['search']) : '';

if (!empty($search)) {
    $sql = "SELECT id, name, type, price, discount, image, quantity, status 
            FROM inventory 
            WHERE name LIKE ?";
    if ($stmt = $conn->prepare($sql)) {
        $searchTerm = "%" . $search . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        die("Lỗi truy vấn: " . $conn->error);
    }
} else {
    $sql = "SELECT id, name, type, price, discount, image, quantity, status FROM inventory";
    if (!$result = $conn->query($sql)) {
        die("Lỗi truy vấn: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Vật Liệu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    
    #main-content {
        height: calc(100vh);
        overflow-y: auto;
        padding: 20px;
        background: #fdf4ff;
    }

    .table-responsive {
        max-height: 500px;
        overflow-y: auto; 
    }

    td img {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    tbody tr:hover {
        background-color: #e7e5e4;
        cursor: pointer;
    }


    </style>
</head>
<body>
    <div class="container mt-5">
        <h3>Quản Lý Vật Liệu</h3>
        
        <form method="POST" action="">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Nhập từ khóa tìm kiếm..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    <a href="?page=expenses" class="btn btn-secondary">✖️ Đặt lại</a>
                    
                </div>
            </div>
        </form>

        <div class="row mb-3">
            <div class="col-12">
                <a class="btn btn-success" href="add/add_expenses.php">+ Thêm Dữ Liệu</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th>ID Sản Phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Loại Sản Phẩm</th>
                        <th>Giá tiền</th>
                        <th>Giảm Giá</th>
                        <th>Ảnh Sản Phẩm</th>
                        <th>Số lượng</th>
                        <th>Tình Trạng</th>
                        <th>Chức Năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["type"] . "</td>";
                            echo "<td>" . $row["price"] . "</td>";
                            echo "<td>" . $row["discount"] . "%" . "</td>";
                            echo '<td><img src="image/' . $row["image"] . '" alt="Ảnh sản phẩm" style="width:100px; height:auto; margin-top:30px;"></td>';
                            echo "<td>" . $row["quantity"] . "</td>";
                            echo "<td>" . $row["status"] . "</td>";
                            echo '<td>
                                    <a href="update/update_expenses.php?id=' . $row["id"] . '" class="btn btn-warning btn-sm">✏️</a>
                                    <a href="delete/dl_expenses.php?id=' . $row["id"] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc chắn muốn xóa không?\');">❌</a>
                                  </td>';
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>Không có dữ liệu phù hợp</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
