<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');
include_once(__DIR__ . '/../../Paginator.php');

// 2. Chuẩn bị câu truy vấn $sql
$stt = 1;
$sql = "select * from `chucvu`";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu (Có phân trang - Pagination)
$limit      = (isset($_GET['limit'])) ? $_GET['limit'] : Config::$LIMIT;
$page       = (isset($_GET['page'])) ? $_GET['page'] : Config::$PAGE;
$paginator  = new Paginator($twig, $conn, $sql);
$data       = $paginator->getData($limit, $page);
// dd($data);

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/chucvu/index.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `dataChucvu`
echo $twig->render('backend/chucvu/index.html.twig', [
    'dataChucvu' => $data,
    'paginator' => $paginator
]);
