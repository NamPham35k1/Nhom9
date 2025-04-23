<?php
include 'connect.php';
$search = isset($_POST['search']) ? $_POST['search'] : '';

if (!empty($search)) {
    $sql = "SELECT id_kh, name, ns, gt, address,sdt FROM client WHERE name LIKE '%$search%'";
} else {
    $sql = "SELECT id_kh, name, ns, gt, address,sdt FROM client";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khách Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        tbody tr:hover {
            background-color: #e7e5e4;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3>Quản Lý Khách Hàng</h3>
        <a class="btn btn-success" href="add/add_client.php" style="width: auto; padding: 10px 10px; margin-bottom:10px">+ Thêm Dữ Liệu</a>

        <form method="POST" action="index.php?page=management" class="mb-3">
             <div class="row g-3">
             <div class="col-md-8">
            <input type="text" name="search" class="form-control" placeholder="Nhập tên Khách Hàng..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
               <div class="col-md-4">
            <button type="submit" class="btn btn-primary">🔍 Tìm kiếm</button>
            <a href="?page=management" class="btn btn-secondary">✖️ Đặt lại</a>
          </div>
            </div>
         </form>


        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Ngày Sinh</th>
                        <th>Giới Tính</th>
                        <th>Địa Chỉ</th>
                        <th>Sdt</th>
                        <th>Tùy chỉnh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id_kh"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["ns"] . "</td>";
                            echo "<td>" . $row["gt"]  . "</td>";
                            echo "<td>" . $row["address"] . "</td>";
                            echo "<td>" . $row["sdt"] . "</td>";
                            echo '<td>
                                    <a href="update/update_client.php?id=' . $row["id_kh"] . '" class="btn btn-warning btn-sm">✏️</a>
                                    <a href="delete/dl_client.php?id=' . $row["id_kh"] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc chắn muốn xóa không?\');">❌</a>
                                  </td>';
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Không tìm thấy kết quả phù hợp.</td></tr>";
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
