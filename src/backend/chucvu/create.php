<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if (isset($_POST['btnSave'])) {
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $cv_ma = $_POST['cv_ma'];
    $cv_ten = $_POST['cv_ten'];
    $cv_diengiai = $_POST['cv_diengiai'];

    // Kiểm tra ràng buộc dữ liệu (Validation)
    // Tạo biến lỗi để chứa thông báo lỗi
    $errors = [];
    // ### Mã chức vụ
    // required
    if (empty($cv_ma)) {
        $errors['cv_ma'][] = [
            'rule' => 'required',
            'rule_value' => true,
            'value' => $cv_ma,
            'msg' => 'Vui lòng nhập mã chức vụ'
        ];
    }
    // minlength 3
    if (!empty($cv_ma) && strlen($cv_ma) < 3) {
        $errors['cv_ma'][] = [
            'rule' => 'minlength',
            'rule_value' => 3,
            'value' => $cv_ma,
            'msg' => 'Mã chức vụ phải có ít nhất 3 ký tự'
        ];
    }
    // maxlength 50
    if (!empty($cv_ma) && strlen($cv_ma) > 50) {
        $errors['cv_ma'][] = [
            'rule' => 'maxlength',
            'rule_value' => 50,
            'value' => $cv_ma,
            'msg' => 'Mã chức vụ không được vượt quá 50 ký tự'
        ];
    }

    // ### Tên chức vụ
    // required
    if (empty($cv_ten)) {
        $errors['cv_ten'][] = [
            'rule' => 'required',
            'rule_value' => true,
            'value' => $cv_ten,
            'msg' => 'Vui lòng nhập tên chức vụ'
        ];
    }
    // minlength 3
    if (!empty($cv_ten) && strlen($cv_ten) < 3) {
        $errors['cv_ten'][] = [
            'rule' => 'minlength',
            'rule_value' => 3,
            'value' => $cv_ten,
            'msg' => 'Tên chức vụ phải có ít nhất 3 ký tự'
        ];
    }
    // maxlength 255
    if (!empty($cv_ten) && strlen($cv_ten) > 255) {
        $errors['cv_ten'][] = [
            'rule' => 'maxlength',
            'rule_value' => 255,
            'value' => $cv_ten,
            'msg' => 'Tên chức vụ không được vượt quá 255 ký tự'
        ];
    }

    // ### Diễn giải Chức vụ
    // maxlength 500
    if (!empty($cv_diengiai) && strlen($cv_diengiai) > 500) {
        $errors['cv_diengiai'][] = [
            'rule' => 'maxlength',
            'rule_value' => 500,
            'value' => $cv_diengiai,
            'msg' => 'Diễn giải chức vụ không được vượt quá 500 ký tự'
        ];
    }

    // dd($errors);
    if (!empty($errors)) {
        // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/chucvu/create.html.twig`
        // kèm theo dữ liệu thông báo lỗi
        echo $twig->render('backend/chucvu/create.html.twig', [
            'errors' => $errors,
            'cv_ma_oldvalue' => $cv_ma,
            'cv_ten_oldvalue' => $cv_ten,
            'cv_diengiai_oldvalue' => $cv_diengiai,
        ]);
    } else { // Nếu không có lỗi dữ liệu sẽ thực thi câu lệnh SQL
        // Câu lệnh INSERT
        $sql = "INSERT INTO chucvu
                (cv_ma, cv_ten, cv_diengiai)
                VALUES ('$cv_ma', '$cv_ten', '$cv_diengiai')";

        // Thực thi INSERT
        mysqli_query($conn, $sql);

        // Đóng kết nối
        mysqli_close($conn);

        // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
        header('location:index.php');
    }
} else {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/chucvu/create.html.twig`
    echo $twig->render('backend/chucvu/create.html.twig');
}
