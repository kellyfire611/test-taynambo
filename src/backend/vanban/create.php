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

$sqlSelectNhanvien = "SELECT * FROM `nhanvien`";
$paginator = new Paginator($twig, $conn, $sqlSelectNhanvien);
$dataNhanvien = $paginator->getData('all', 1);
// dd($dataNhanvien);

$sqlSelectChucvu = "SELECT * FROM `chucvu`";
$paginator = new Paginator($twig, $conn, $sqlSelectChucvu);
$dataChucvu = $paginator->getData('all', 1);
// dd($dataChucvu);

/* --- 
   --- 2.Truy vấn dữ liệu Văn bản
   --- 
*/
// Chuẩn bị câu truy vấn
$sqlVanBan = "select * from `vanban`";

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
    $vb_nguoiky_hoten = '';
    $vb_nguoiky_chucdanh = '';
    $vb_ngayky = empty($_POST['vb_ngayky']) ? 'NULL' : "'" . $_POST['vb_ngayky'] . "'";
    $vb_ngayhieuluc = empty($_POST['vb_ngayhieuluc']) ? 'NULL' : "'" . $_POST['vb_ngayhieuluc'] . "'";
    $vb_taptin_dinhkem = "";
    $vb_lienquan = isset($_POST['vb_lienquan']) ? $_POST['vb_lienquan'] : [];
    $vb_nguoiky_nhanvien_id = $_POST['vb_nguoiky_nhanvien_id'];
    $vb_nguoiky_chucvu_id = $_POST['vb_nguoiky_chucvu_id'];
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
    //     $vb_lienquan,
    //     $vb_lienquan_str,
    //     $vb_nguoiky_nhanvien_id,
    //     $vb_nguoiky_chucvu_id
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

        // Câu lệnh INSERT
        $sql = "INSERT INTO vanban (vb_so, vb_tieude, vb_trichyeu, vb_phongban_banhanh_id, vb_nguoiky_hoten, vb_nguoiky_chucdanh, vb_ngayky, vb_ngayhieuluc, vb_taptin_dinhkem, vb_lienquan, vb_nguoiky_nhanvien_id, vb_nguoiky_chucvu_id)
                VALUES ('$vb_so', '$vb_tieude', '$vb_trichyeu', $vb_phongban_banhanh_id, '$vb_nguoiky_hoten', '$vb_nguoiky_chucdanh', $vb_ngayky, $vb_ngayhieuluc, '$vb_taptin_dinhkem', $vb_lienquan_str, $vb_nguoiky_nhanvien_id, $vb_nguoiky_chucvu_id)";

        // var_dump($sql);
        // Thực thi INSERT
        mysqli_query($conn, $sql);

        // Đóng kết nối
        mysqli_close($conn);

        // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
        header('location:index.php');
    }
} else {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/vanban/create.html.twig`
    // dd($dataVanBan, $dataPhongban, $dataNhanvien, $dataChucvu);
    echo $twig->render('backend/vanban/create.html.twig', [
        'dataVanBan' => $dataVanBan,
        'dataPhongban' => $dataPhongban,
        'dataNhanvien' => $dataNhanvien,
        'dataChucvu' => $dataChucvu
    ]);
}
