<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');
include_once(__DIR__ . '/../../Paginator.php');

/* --- 
   --- 2.Truy vấn dữ liệu Phòng ban 
   --- 
*/
// Chuẩn bị câu truy vấn Phòng ban
$sqlPhongBan = "select * from `phongban`";

// Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultPhongBan = mysqli_query($conn, $sqlPhongBan);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataPhongBan = [];
while($rowPhongBan = mysqli_fetch_array($resultPhongBan, MYSQLI_ASSOC))
{
    $dataPhongBan[] = array(
        'pb_id' => $rowPhongBan['pb_id'],
        'pb_ma' => $rowPhongBan['pb_ma'],
        'pb_ten' => $rowPhongBan['pb_ten'],
        'pb_diengiai' => $rowPhongBan['pb_diengiai'],
    );
}
/* --- End Truy vấn dữ liệu Phòng ban --- */

// Giữ lại keyword mà người dùng tìm kiếm
$keyword_vb_so = isset($_GET['keyword_vb_so']) ? $_GET['keyword_vb_so'] : '';
$keyword_vb_tieude = isset($_GET['keyword_vb_tieude']) ? $_GET['keyword_vb_tieude'] : '';
$keyword_vb_trichyeu = isset($_GET['keyword_vb_trichyeu']) ? $_GET['keyword_vb_trichyeu'] : '';
$keyword_vb_phongban_banhanh_id = isset($_GET['keyword_vb_phongban_banhanh_id']) ? $_GET['keyword_vb_phongban_banhanh_id'] : [];
$keyword_vb_nguoiky_hoten = isset($_GET['keyword_vb_nguoiky_hoten']) ? $_GET['keyword_vb_nguoiky_hoten'] : '';
$keyword_vb_nguoiky_chucdanh = isset($_GET['keyword_vb_nguoiky_chucdanh']) ? $_GET['keyword_vb_nguoiky_chucdanh'] : '';
$keyword_vb_ngayky = isset($_GET['keyword_vb_ngayky']) ? $_GET['keyword_vb_ngayky'] : '';
$keyword_vb_ngayhieuluc = isset($_GET['keyword_vb_ngayhieuluc']) ? $_GET['keyword_vb_ngayhieuluc'] : '';

// 2. Chuẩn bị câu truy vấn $sql
$sql = "
    SELECT vb.*,
        pb.pb_ma, pb.pb_ten
    FROM `vanban` vb
    JOIN `phongban` pb ON vb.vb_phongban_banhanh_id = pb.pb_id
";

$sqlWhereArr = [];
// Tìm theo số văn bản
if (!empty($keyword_vb_so)) {
    $sqlWhereArr[] = "vb.vb_so LIKE '%$keyword_vb_so%'";
}
// Tìm theo tiêu đề văn bản
if (!empty($keyword_vb_tieude)) {
    $sqlWhereArr[] = "vb.vb_tieude LIKE '%$keyword_vb_tieude%'";
}
// Tìm theo trích yếu văn bản
if (!empty($keyword_vb_trichyeu)) {
    $sqlWhereArr[] = "vb.vb_trichyeu LIKE '%$keyword_vb_trichyeu%'";
}
// Tìm theo trích yếu văn bản
if (!empty($keyword_vb_phongban_banhanh_id)) {
    $sqlWhereArr[] = "vb.vb_phongban_banhanh_id = '$keyword_vb_phongban_banhanh_id'";
}
// Tìm theo người ký
if (!empty($keyword_vb_nguoiky_hoten)) {
    $sqlWhereArr[] = "vb.vb_nguoiky_hoten LIKE '%$keyword_vb_nguoiky_hoten%'";
}
// Tìm theo chức danh
if (!empty($keyword_vb_nguoiky_chucdanh)) {
    $sqlWhereArr[] = "vb.vb_nguoiky_chucdanh LIKE '%$keyword_vb_nguoiky_chucdanh%'";
}
// Tìm theo ngày ký
if (!empty($keyword_vb_ngayky)) {
    $sqlWhereArr[] = "vb.vb_ngayky >= '$keyword_vb_ngayky'";
}
// Tìm theo ngày hiệu lực
if (!empty($keyword_vb_ngayhieuluc)) {
    $sqlWhereArr[] = "vb.vb_ngayhieuluc >= '$keyword_vb_ngayhieuluc'";
}

// Câu lệnh cuối cùng
if (count($sqlWhereArr) > 0) {
    $sqlWhere = "WHERE " . implode(' AND ', $sqlWhereArr);
    $sql .= $sqlWhere;
}
$sql .= <<<EOT
    ORDER BY vb.vb_ngayhieuluc DESC
EOT;
// dd($sql);

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu (Có phân trang - Pagination)
$limit      = (isset($_GET['limit'])) ? $_GET['limit'] : Config::$LIMIT;
$page       = (isset($_GET['page'])) ? $_GET['page'] : Config::$PAGE;
$paginator  = new Paginator($twig, $conn, $sql);
$data       = $paginator->getData($limit, $page);

foreach($data->data as $index => $row) {
    $vb_lienquan_ids = $row["vb_lienquan"];
    if(!empty($vb_lienquan_ids)) {
        /* --- 
        --- 2.Truy vấn dữ liệu Văn bản liên quan
        --- 
        */
        // Chuẩn bị câu truy vấn
        $sqlVanBanLienQuan = "select * from `vanban` WHERE vb_id IN ($vb_lienquan_ids)";

        // Thực thi câu truy vấn SQL để lấy về dữ liệu
        $resultVanBanLienQuan = mysqli_query($conn, $sqlVanBanLienQuan);

        // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
        $dataVanBanLienQuan = [];
        while($rowVanBanLienQuan = mysqli_fetch_array($resultVanBanLienQuan, MYSQLI_ASSOC))
        {
            $dataVanBanLienQuan[] = array(
                'vb_id' => $rowVanBanLienQuan['vb_id'],
                'vb_so' => $rowVanBanLienQuan['vb_so'],
                'vb_tieude' => $rowVanBanLienQuan['vb_tieude'],
                'vb_taptin_dinhkem' => $rowVanBanLienQuan['vb_taptin_dinhkem'],
            );
        }
        $data->data[$index]['vb_lienquan_relationship'] = $dataVanBanLienQuan;
        /* --- End Truy vấn dữ liệu Văn bản --- */
    }
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/vanban/index.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `dataVanBan`
echo $twig->render('backend/vanban/index.html.twig', [
    'dataVanBan' => $data,
    'dataPhongBan' => $dataPhongBan,
    'keyword_vb_so' => $keyword_vb_so,
    'keyword_vb_tieude' => $keyword_vb_tieude,
    'keyword_vb_trichyeu' => $keyword_vb_trichyeu,
    'keyword_vb_phongban_banhanh_id' => $keyword_vb_phongban_banhanh_id,
    'keyword_vb_nguoiky_hoten' => $keyword_vb_nguoiky_hoten,
    'keyword_vb_nguoiky_chucdanh' => $keyword_vb_nguoiky_chucdanh,
    'keyword_vb_ngayky' => $keyword_vb_ngayky,
    'keyword_vb_ngayhieuluc' => $keyword_vb_ngayhieuluc,
    'paginator' => $paginator
]);