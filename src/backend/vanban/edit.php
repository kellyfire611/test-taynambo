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

// 2. Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record cần update
// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$vb_id = $_GET['id'];
$sqlSelect = "SELECT * FROM `vanban` WHERE vb_id=$vb_id;";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record cần update
$resultSelect = mysqli_query($conn, $sqlSelect);
$vanbanRow = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC); // 1 record
$vanbanRow['vb_lienquan_arr'] = explode(',', $vanbanRow["vb_lienquan"]);

/* --- 
   --- 2.Truy vấn dữ liệu Văn bản
   --- 
*/
// Chuẩn bị câu truy vấn
$sqlVanBan = "select * from `vanban` WHERE vb_id != $vb_id";

// Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultVanBan = mysqli_query($conn, $sqlVanBan);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataVanBan = [];
while($rowVanBan = mysqli_fetch_array($resultVanBan, MYSQLI_ASSOC))
{
    $dataVanBan[] = array(
        'vb_id' => $rowVanBan['vb_id'],
        'vb_so' => $rowVanBan['vb_so'],
        'vb_tieude' => $rowVanBan['vb_tieude'],
    );
}
/* --- End Truy vấn dữ liệu Văn bản --- */

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
    $vb_taptin_dinhkem = $vanbanRow['vb_taptin_dinhkem'];
    $vb_lienquan = $_POST['vb_lienquan'];
    if(empty($vb_lienquan)) {
        $vb_lienquan_str = 'NULL';
    } else {
        $vb_lienquan_str = "'" . implode(',', $vb_lienquan) . "'";
    }
    // dd($vb_so,
    //     $vb_tieude,
    //     $vb_trichyeu,
    //     $vb_phongban_banhanh_id,
    //     $vb_nguoiky_hoten,
    //     $vb_nguoiky_chucdanh,
    //     $vb_ngayky,
    //     $vb_ngayhieuluc,
    //     $vb_taptin_dinhkem,
    //     $vb_lienquan_str,
    // );

    // Kiểm tra ràng buộc dữ liệu (Validation)
    // Tạo biến lỗi để chứa thông báo lỗi
    $errors = [];

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

        // Nếu người dùng có chọn file để upload
        if (isset($_FILES['vb_taptin_dinhkem']) && !empty($_FILES['vb_taptin_dinhkem']['name']))
        {
            // Đường dẫn để chứa thư mục upload trên ứng dụng web của chúng ta. Các bạn có thể tùy chỉnh theo ý các bạn.
            // Ví dụ: các file upload sẽ được lưu vào thư mục ../../assets/uploads
            $upload_dir = "./../../assets/uploads/vanban/";

            $old_file = $upload_dir.$vanbanRow['vb_taptin_dinhkem'];
            if(file_exists($old_file)) {
                unlink($old_file);
            }
            // dd($_FILES);

            // Đối với mỗi file, sẽ có các thuộc tính như sau:
            // $_FILES['vb_taptin_dinhkem']['name']     : Tên của file chúng ta upload
            // $_FILES['vb_taptin_dinhkem']['type']     : Kiểu file mà chúng ta upload (hình ảnh, word, excel, pdf, txt, ...)
            // $_FILES['vb_taptin_dinhkem']['tmp_name'] : Đường dẫn đến file tạm trên web server
            // $_FILES['vb_taptin_dinhkem']['error']    : Trạng thái của file chúng ta upload, 0 => không có lỗi
            // $_FILES['vb_taptin_dinhkem']['size']     : Kích thước của file chúng ta upload

            // Nếu file upload bị lỗi, tức là thuộc tính error > 0
            if ($_FILES['vb_taptin_dinhkem']['error'] > 0)
            {
                echo 'File Upload Bị Lỗi';die;
            }
            else{
                // Tiến hành di chuyển file từ thư mục tạm trên server vào thư mục chúng ta muốn chứa các file uploads
                // Ví dụ: move file từ C:\xampp\tmp\php6091.tmp -> C:/xampp/htdocs/learning.nentang.vn/assets/uploads/hoahong.jpg
                $vb_taptin_dinhkem = date('YmdHis') . '_' . $_FILES['vb_taptin_dinhkem']['name'];
                move_uploaded_file($_FILES['vb_taptin_dinhkem']['tmp_name'], $upload_dir.$vb_taptin_dinhkem);
                echo 'File Uploaded';
            }
        }

        // Câu lệnh UPDATE
        $sql = "
        UPDATE vanban
        SET
            vb_so='$vb_so',
            vb_tieude='$vb_tieude',
            vb_trichyeu='$vb_trichyeu',
            vb_phongban_banhanh_id=$vb_phongban_banhanh_id,
            vb_nguoiky_hoten='$vb_nguoiky_hoten',
            vb_nguoiky_chucdanh='$vb_nguoiky_chucdanh',
            vb_ngayky='$vb_ngayky',
            vb_ngayhieuluc='$vb_ngayhieuluc',
            vb_taptin_dinhkem='$vb_taptin_dinhkem',
            vb_lienquan=$vb_lienquan_str
        WHERE vb_id=$vb_id";

        // Thực thi UPDATE
        mysqli_query($conn, $sql);

        // Đóng kết nối
        mysqli_close($conn);

        // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
        header('location:index.php');
    }
} else {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/vanban/edit.html.twig`
    echo $twig->render('backend/vanban/edit.html.twig', [
        'dataPhongBan' => $dataPhongBan,
        'dataVanBan' => $dataVanBan,
        'vanbanRow' => $vanbanRow,
    ]);
}
