<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if (isset($_POST['btnSave'])) {
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $pb_ma = $_POST['pb_ma'];
    $pb_ten = $_POST['pb_ten'];
    $pb_diengiai = $_POST['pb_diengiai'];

    // Kiểm tra ràng buộc dữ liệu (Validation)
    // Tạo biến lỗi để chứa thông báo lỗi
    $errors = [];
    // ### Mã phòng ban
    // required
    if (empty($pb_ma)) {
        $errors['pb_ma'][] = [
            'rule' => 'required',
            'rule_value' => true,
            'value' => $pb_ma,
            'msg' => 'Vui lòng nhập mã Phòng ban'
        ];
    }
    // minlength 3
    if (!empty($pb_ma) && strlen($pb_ma) < 3) {
        $errors['pb_ma'][] = [
            'rule' => 'minlength',
            'rule_value' => 3,
            'value' => $pb_ma,
            'msg' => 'Mã Phòng ban phải có ít nhất 3 ký tự'
        ];
    }
    // maxlength 50
    if (!empty($pb_ma) && strlen($pb_ma) > 50) {
        $errors['pb_ma'][] = [
            'rule' => 'maxlength',
            'rule_value' => 50,
            'value' => $pb_ma,
            'msg' => 'Mã Phòng ban không được vượt quá 50 ký tự'
        ];
    }

    // ### Tên phòng ban
    // required
    if (empty($pb_ten)) {
        $errors['pb_ten'][] = [
            'rule' => 'required',
            'rule_value' => true,
            'value' => $pb_ten,
            'msg' => 'Vui lòng nhập tên Phòng ban'
        ];
    }
    // minlength 3
    if (!empty($pb_ten) && strlen($pb_ten) < 3) {
        $errors['pb_ten'][] = [
            'rule' => 'minlength',
            'rule_value' => 3,
            'value' => $pb_ten,
            'msg' => 'Tên Phòng ban phải có ít nhất 3 ký tự'
        ];
    }
    // maxlength 255
    if (!empty($pb_ten) && strlen($pb_ten) > 255) {
        $errors['pb_ten'][] = [
            'rule' => 'maxlength',
            'rule_value' => 255,
            'value' => $pb_ten,
            'msg' => 'Tên Phòng ban không được vượt quá 255 ký tự'
        ];
    }

    // ### Diễn giải phòng ban
    // maxlength 500
    if (!empty($pb_diengiai) && strlen($pb_diengiai) > 500) {
        $errors['pb_diengiai'][] = [
            'rule' => 'maxlength',
            'rule_value' => 500,
            'value' => $pb_diengiai,
            'msg' => 'Diễn giải Phòng ban không được vượt quá 500 ký tự'
        ];
    }

    // dd($errors);
    if (!empty($errors)) {
        // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/phongban/create.html.twig`
        // kèm theo dữ liệu thông báo lỗi
        echo $twig->render('backend/phongban/create.html.twig', [
            'errors' => $errors,
            'pb_ma_oldvalue' => $pb_ma,
            'pb_ten_oldvalue' => $pb_ten,
            'pb_diengiai_oldvalue' => $pb_diengiai,
        ]);
    } else { // Nếu không có lỗi dữ liệu sẽ thực thi câu lệnh SQL
        // Câu lệnh INSERT
        $sql = "INSERT INTO phongban
                (pb_ma, pb_ten, pb_diengiai)
                VALUES ('$pb_ma', '$pb_ten', '$pb_diengiai')";

        // Thực thi INSERT
        mysqli_query($conn, $sql);

        // Đóng kết nối
        mysqli_close($conn);

        // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
        header('location:index.php');
    }
} else {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/phongban/create.html.twig`
    echo $twig->render('backend/phongban/create.html.twig');
}
