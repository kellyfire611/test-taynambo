<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

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

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if (isset($_POST['btnSave'])) {
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $vb_so = $_POST['vb_so'];
    $vb_tieude = $_POST['vb_tieude'];
    $vb_trichyeu = $_POST['vb_trichyeu'];
    $vb_phongban_banhanh_id = $_POST['vb_phongban_banhanh_id'];
    $vb_nguoiky_hoten = $_POST['vb_nguoiky_hoten'];
    $vb_nguoiky_chucdanh = $_POST['vb_nguoiky_chucdanh'];
    $vb_ngayky = $_POST['vb_ngayky'];
    $vb_ngayhieuluc = $_POST['vb_ngayhieuluc'];
    $vb_taptin_dinhkem;

    // Kiểm tra ràng buộc dữ liệu (Validation)
    // Tạo biến lỗi để chứa thông báo lỗi
    $errors = [];
    // ### Số văn bản
    // required
    if (empty($vb_so)) {
        $errors['vb_so'][] = [
            'rule' => 'required',
            'rule_value' => true,
            'value' => $vb_so,
            'msg' => 'Vui lòng nhập mã Phòng ban'
        ];
    }
    // minlength 3
    if (!empty($vb_so) && strlen($vb_so) < 3) {
        $errors['vb_so'][] = [
            'rule' => 'minlength',
            'rule_value' => 3,
            'value' => $vb_so,
            'msg' => 'Mã Phòng ban phải có ít nhất 3 ký tự'
        ];
    }
    // maxlength 50
    if (!empty($vb_so) && strlen($vb_so) > 50) {
        $errors['vb_so'][] = [
            'rule' => 'maxlength',
            'rule_value' => 50,
            'value' => $vb_so,
            'msg' => 'Mã Phòng ban không được vượt quá 50 ký tự'
        ];
    }

    // ### Tên phòng ban
    // required
    if (empty($vb_tieude)) {
        $errors['vb_tieude'][] = [
            'rule' => 'required',
            'rule_value' => true,
            'value' => $vb_tieude,
            'msg' => 'Vui lòng nhập tên Phòng ban'
        ];
    }
    // minlength 3
    if (!empty($vb_tieude) && strlen($vb_tieude) < 3) {
        $errors['vb_tieude'][] = [
            'rule' => 'minlength',
            'rule_value' => 3,
            'value' => $vb_tieude,
            'msg' => 'Tên Phòng ban phải có ít nhất 3 ký tự'
        ];
    }
    // maxlength 255
    if (!empty($vb_tieude) && strlen($vb_tieude) > 255) {
        $errors['vb_tieude'][] = [
            'rule' => 'maxlength',
            'rule_value' => 255,
            'value' => $vb_tieude,
            'msg' => 'Tên Phòng ban không được vượt quá 255 ký tự'
        ];
    }

    // ### Diễn giải phòng ban
    // maxlength 500
    if (!empty($vb_trichyeu) && strlen($vb_trichyeu) > 500) {
        $errors['vb_trichyeu'][] = [
            'rule' => 'maxlength',
            'rule_value' => 500,
            'value' => $vb_trichyeu,
            'msg' => 'Diễn giải Phòng ban không được vượt quá 500 ký tự'
        ];
    }

    // dd($errors);
    if (!empty($errors)) {
        // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/vanban/create.html.twig`
        // kèm theo dữ liệu thông báo lỗi
        echo $twig->render('backend/vanban/create.html.twig', [
            'errors' => $errors,
            'vb_so_oldvalue' => $vb_so,
            'vb_tieude_oldvalue' => $vb_tieude,
            'vb_trichyeu_oldvalue' => $vb_trichyeu,
        ]);
    } else { // Nếu không có lỗi dữ liệu sẽ thực thi câu lệnh SQL
        // Câu lệnh INSERT
        $sql = "INSERT INTO vanban (vb_so, vb_tieude, vb_trichyeu, vb_phongban_banhanh_id, vb_nguoiky_hoten, vb_nguoiky_chucdanh, vb_ngayky, vb_ngayhieuluc, vb_taptin_dinhkem)
                VALUES ('', '', '', 0, '', '', NOW(), NOW(), '')";

        // Thực thi INSERT
        mysqli_query($conn, $sql);

        // Đóng kết nối
        mysqli_close($conn);

        // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
        header('location:index.php');
    }
} else {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/vanban/create.html.twig`
    echo $twig->render('backend/vanban/create.html.twig', [
        'dataPhongBan' => $dataPhongBan,
    ]);
}
