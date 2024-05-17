<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

// 2. Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record cần update
// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$cv_id = $_GET['id'];
$sqlSelect = "SELECT * FROM `chucvu` WHERE cv_id=$cv_id;";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record cần update
$resultSelect = mysqli_query($conn, $sqlSelect);
$chucvuRow = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC); // 1 record

// 4. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if(isset($_POST['btnSave'])) 
{
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $cv_ma = $_POST['cv_ma'];
    $cv_ten = $_POST['cv_ten'];
    $cv_diengiai = $_POST['cv_diengiai'];

    // Câu lệnh UPDATE
    $sql = "
    UPDATE chucvu
    SET
        cv_ma='$cv_ma',
        cv_ten='$cv_ten',
        cv_diengiai='$cv_diengiai'
    WHERE cv_id=$cv_id";

    // Thực thi UPDATE
    mysqli_query($conn, $sql);

    // Đóng kết nối
    mysqli_close($conn);

    // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
    header('location:index.php');
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/chucvu/edit.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `chucvu`
// dd($chucvuRow);
echo $twig->render('backend/chucvu/edit.html.twig', ['chucvuRow' => $chucvuRow] );