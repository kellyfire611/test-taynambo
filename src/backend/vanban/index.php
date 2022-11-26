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
    SELECT vb.*,
        pb.pb_ma, pb.pb_ten
    FROM `vanban` vb
    JOIN `phongban` pb ON vb.vb_phongban_banhanh_id = pb.pb_id
";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu (Có phân trang - Pagination)
$limit      = (isset($_GET['limit'])) ? $_GET['limit'] : Config::$LIMIT;
$page       = (isset($_GET['page'])) ? $_GET['page'] : Config::$PAGE;
$paginator  = new Paginator($twig, $conn, $sql);
$data       = $paginator->getData($limit, $page);

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/vanban/index.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `dataVanBan`
echo $twig->render('backend/vanban/index.html.twig', [
    'dataVanBan' => $data,
    'paginator' => $paginator
]);
