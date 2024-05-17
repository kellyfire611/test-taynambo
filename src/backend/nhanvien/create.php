<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');
include_once(__DIR__ . '/../../Paginator.php');

$sqlSelectPhongban = "SELECT * FROM `phongban`";
$paginator = new Paginator($twig, $conn, $sqlSelectPhongban);
$dataPhongban = $paginator->getData('all', 1);
// dd($dataPhongban);

$sqlSelectChucvu = "SELECT * FROM `chucvu`";
$paginator = new Paginator($twig, $conn, $sqlSelectChucvu);
$dataChucvu = $paginator->getData('all', 1);
// dd($dataChucvu);

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if (isset($_POST['btnSave'])) {
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $nv_ma = $_POST['nv_ma'];
    $nv_hoten = $_POST['nv_hoten'];
    $nv_gioitinh = $_POST['nv_gioitinh'];
    $nv_phongban_id = $_POST['nv_phongban_id'];
    $nv_chucvu_id = $_POST['nv_chucvu_id'];

    // Kiểm tra ràng buộc dữ liệu (Validation)
    // Tạo biến lỗi để chứa thông báo lỗi
    $errors = [];
    // ### Mã phòng ban
    // required
    if (empty($nv_ma)) {
        $errors['nv_ma'][] = [
            'rule' => 'required',
            'rule_value' => true,
            'value' => $nv_ma,
            'msg' => 'Vui lòng nhập mã Nhân viên'
        ];
    }
    // minlength 3
    if (!empty($nv_ma) && strlen($nv_ma) < 3) {
        $errors['nv_ma'][] = [
            'rule' => 'minlength',
            'rule_value' => 3,
            'value' => $nv_ma,
            'msg' => 'Mã Nhân viên phải có ít nhất 3 ký tự'
        ];
    }
    // maxlength 50
    if (!empty($nv_ma) && strlen($nv_ma) > 50) {
        $errors['nv_ma'][] = [
            'rule' => 'maxlength',
            'rule_value' => 50,
            'value' => $nv_ma,
            'msg' => 'Mã Nhân viên không được vượt quá 50 ký tự'
        ];
    }

    // ### Tên phòng ban
    // required
    if (empty($nv_hoten)) {
        $errors['nv_hoten'][] = [
            'rule' => 'required',
            'rule_value' => true,
            'value' => $nv_hoten,
            'msg' => 'Vui lòng nhập tên Nhân viên'
        ];
    }
    // minlength 3
    if (!empty($nv_hoten) && strlen($nv_hoten) < 3) {
        $errors['nv_hoten'][] = [
            'rule' => 'minlength',
            'rule_value' => 3,
            'value' => $nv_hoten,
            'msg' => 'Tên Nhân viên phải có ít nhất 3 ký tự'
        ];
    }
    // maxlength 255
    if (!empty($nv_hoten) && strlen($nv_hoten) > 255) {
        $errors['nv_hoten'][] = [
            'rule' => 'maxlength',
            'rule_value' => 255,
            'value' => $nv_hoten,
            'msg' => 'Tên Nhân viên không được vượt quá 255 ký tự'
        ];
    }

    // dd($errors);
    if (!empty($errors)) {
        // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/nhanvien/create.html.twig`
        // kèm theo dữ liệu thông báo lỗi
        echo $twig->render('backend/nhanvien/create.html.twig', [
            'errors' => $errors,
            'nv_ma_oldvalue' => $nv_ma,
            'nv_hoten_oldvalue' => $nv_hoten,
            'nv_gioitinh_oldvalue' => $nv_gioitinh,
            'nv_phongban_id_oldvalue' => $nv_phongban_id,
            'nv_chucvu_id_oldvalue' => $nv_chucvu_id,
        ]);
    } else { // Nếu không có lỗi dữ liệu sẽ thực thi câu lệnh SQL
        // Câu lệnh INSERT
        $sql = "INSERT INTO nhanvien
                (nv_ma, nv_hoten, nv_gioitinh, nv_phongban_id, nv_chucvu_id)
                VALUES ('$nv_ma', '$nv_hoten', '$nv_gioitinh', $nv_phongban_id, $nv_chucvu_id)";

        // Thực thi INSERT
        mysqli_query($conn, $sql);

        // Đóng kết nối
        mysqli_close($conn);

        // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
        header('location:index.php');
    }
} else {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/nhanvien/create.html.twig`
    echo $twig->render('backend/nhanvien/create.html.twig',
    [
        'dataPhongban' => $dataPhongban,
        'dataChucvu' => $dataChucvu
    ]);
}
