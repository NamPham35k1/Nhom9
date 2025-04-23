<?php
// Kết nối đến cơ sở dữ liệu
include '../connect.php'; // Đường dẫn quay về file connect.php ở thư mục cha

// Kiểm tra xem tham số id có tồn tại không
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Câu lệnh SQL để xóa
    $sql = "DELETE FROM supplier WHERE id = ?";
    
    // Sử dụng prepared statement để bảo mật
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // "i" là kiểu INT cho id

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        header("Location: ../index.php?page=suppliers");
        exit();
    } else {
        echo "Lỗi khi xóa dữ liệu: " . $stmt->error;
    }

    $stmt->close();
} else { 
    echo "Không tìm thấy ID để xóa.";
}

$conn->close();
?>
