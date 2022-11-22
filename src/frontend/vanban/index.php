<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');
include_once(__DIR__ . '/../../Paginator.php');

// 2. Chuẩn bị câu truy vấn $sql
$stt = 1;
$sql = "select * from `vanban`";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu (Có phân trang - Pagination)
$limit      = (isset($_GET['limit'])) ? $_GET['limit'] : 5;
$page       = (isset($_GET['page'])) ? $_GET['page'] : 1;
$paginator  = new Paginator($twig, $conn, $sql);
$data       = $paginator->getData($limit, $page);
// dd($results);

// Yêu cầu `Twig` vẽ giao diện được viết trong file `frontend/vanban/vanban.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `ds_vanban`
echo $twig->render('frontend/vanban/index.html.twig', [
    'ds_vanban' => $data,
    'paginator' => $paginator
]);
