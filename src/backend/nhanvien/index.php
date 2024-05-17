<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');
include_once(__DIR__ . '/../../Paginator.php');

// 2. Chuẩn bị câu truy vấn $sql
$stt = 1;
$sql = "
    SELECT *
    FROM nhanvien nv
        JOIN phongban pb ON nv.nv_phongban_id = pb.pb_id
        JOIN chucvu cv ON nv.nv_chucvu_id = cv.cv_id
";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu (Có phân trang - Pagination)
$limit      = (isset($_GET['limit'])) ? $_GET['limit'] : Config::$LIMIT;
$page       = (isset($_GET['page'])) ? $_GET['page'] : Config::$PAGE;
$paginator  = new Paginator($twig, $conn, $sql);
$data       = $paginator->getData($limit, $page);
// dd($data);

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/nhanvien/index.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `dataNhanvien`
echo $twig->render('backend/nhanvien/index.html.twig', [
    'dataNhanvien' => $data,
    'paginator' => $paginator
]);
