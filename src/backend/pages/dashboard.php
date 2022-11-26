<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

// 2. Chuẩn bị câu truy vấn $sql
$sqlCountVanBan = "SELECT COUNT(*) AS SoLuong FROM `vanban`;";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultCountVanBan = mysqli_query($conn, $sqlCountVanBan);

// 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataCountVanBan = mysqli_fetch_assoc($resultCountVanBan);

// 2. Chuẩn bị câu truy vấn $sql
$sqlCountPhongBan = "SELECT COUNT(*) AS SoLuong FROM `phongban`;";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultCountPhongBan = mysqli_query($conn, $sqlCountPhongBan);

// 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataCountPhongBan = mysqli_fetch_assoc($resultCountPhongBan);
// dd($dataCountPhongBan, $dataCountVanBan);

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/pages/dashboard.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `products`
echo $twig->render('backend/pages/dashboard.html.twig', [
    'dataCountVanBan' => $dataCountVanBan,
    'dataCountPhongBan' => $dataCountPhongBan,
]);