<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

// 2. Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record cần update
// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$nv_id = $_GET['id'];
$sqlSelect = "SELECT * FROM `nhanvien` WHERE nv_id=$nv_id;";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record cần update
$resultSelect = mysqli_query($conn, $sqlSelect);
$nhanvienRow = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC); // 1 record

$sqlSelectPhongban = "SELECT * FROM `phongban`";
$paginator = new Paginator($twig, $conn, $sqlSelectPhongban);
$dataPhongban = $paginator->getData('all', 1);
// dd($dataPhongban);

$sqlSelectChucvu = "SELECT * FROM `chucvu`";
$paginator = new Paginator($twig, $conn, $sqlSelectChucvu);
$dataChucvu = $paginator->getData('all', 1);
// dd($dataChucvu);

// 4. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if(isset($_POST['btnSave'])) 
{
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $nv_ma = $_POST['nv_ma'];
    $nv_hoten = $_POST['nv_hoten'];
    $nv_gioitinh = $_POST['nv_gioitinh'];
    $nv_phongban_id = $_POST['nv_phongban_id'];
    $nv_chucvu_id = $_POST['nv_chucvu_id'];

    // Câu lệnh UPDATE
    $sql = "
    UPDATE nhanvien
    SET
        nv_ma='$nv_ma',
        nv_hoten='$nv_hoten',
        nv_gioitinh='$nv_gioitinh',
        nv_phongban_id='$nv_phongban_id',
        nv_chucvu_id='$nv_chucvu_id'
    WHERE nv_id=$nv_id";

    // Thực thi UPDATE
    mysqli_query($conn, $sql);

    // Đóng kết nối
    mysqli_close($conn);

    // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
    header('location:index.php');
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/nhanvien/edit.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `nhanvien`
// dd($nhanvienRow);
echo $twig->render('backend/nhanvien/edit.html.twig', 
[
    'nhanvienRow' => $nhanvienRow,
    'dataPhongban' => $dataPhongban,
    'dataChucvu' => $dataChucvu
]);