<?php
// Kết nối đến cơ sở dữ liệu
include '../connect.php'; // Đường dẫn quay về file connect.php ở thư mục cha

// Kiểm tra xem tham số id có tồn tại không
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Kiểm tra xem id có tồn tại trong bảng bill_export hay không
    $sql_check = "SELECT * FROM bill_export WHERE employee_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id); // "i" là kiểu INT cho id
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Nếu tồn tại, thông báo không thể xóa
        echo "<div class='alert alert-warning'>Nhân viên còn tồn tại trong Hóa đơn nên không thể xóa!</div>";
    } else {
        // Nếu không tồn tại, tiến hành xóa nhân viên
        $sql_delete = "DELETE FROM user WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            header("Location: ../index.php?page=user");
            exit();
        } else {
            echo "Lỗi khi xóa dữ liệu: " . $stmt_delete->error;
        }

        $stmt_delete->close();
    }

    $stmt_check->close();
} else { 
    echo "Không tìm thấy ID để xóa.";
}

$conn->close();
?>
