# Tác giả:
- Đỗ Thị Minh Mẫn

# Các chương trình cần có để chạy source code
- XAMPP: phiên bản PHP 7+ trở lên. Link download: https://www.apachefriends.org/download.html
- Visual Studio Code IDE: chương trình dùng để viết code lập trình PHP. Link download: https://code.visualstudio.com/download

# Hướng dẫn Setup Source code
## Download và giải nén source code
- Sau khi tải file nén `test-taynambo-dothiminhman.zip` thì giải nén vào thư mục phù hợp trên máy tính cá nhân.
- Ví dụ: giải nén thành `C:\xampp\htdocs\test-taynambo-main`

## Setup môi trường chạy code PHP
### Step 1: tạo host ảo (Virtual host) cho XAMPP
- Hiệu chỉnh file `C:xampp\apache\conf\extra\httpd-vhosts.conf`
- Bổ sung đoạn code sau:
```
<VirtualHost *:80>
    #Thay thế bằng email của Quản trị web của bạn
	ServerAdmin webmaster@dtmman.taynambo
	
	#Thay thế bằng đường dẫn đến source của bạn
    DocumentRoot "C:/xampp/htdocs/test-taynambo-main/src"
	
	#Thay thế bằng tên trang web bạn mong muốn
    ServerName  dtmman.taynambo
	
	#Thay thế bằng tên file log bạn mong muốn
    ErrorLog "logs/dtmman.taynambo-error.log"
    CustomLog "logs/dtmman.taynambo-access.log" common
	
	#Thay thế bằng đường dẫn đến source của bạn
    <Directory "C:/xampp/htdocs/test-taynambo-main/src">
        Options FollowSymLinks
        AllowOverride All
        DirectoryIndex index.php
        Require all granted
    </Directory>
</VirtualHost>
```

### Step 2: add host cho Window
- Hiệu chỉnh file `C:\Windows\System32\drivers\etc\hosts`
- Lưu ý: để hiệu chỉnh file hệ thống, bạn cần quyền Administrator
- Bổ sung dòng code sau
```
127.0.0.1		dtmman.taynambo
```

## Cấu hình database
### Step 3: Restore database
- Khởi động dịch vụ MySQL của XAMPP
- Chạy file script `C:\xampp\htdocs\test-taynambo-main\db\db_test_taynambo.sql` để khôi phục database

### Step 4: Cấu hình thông tin kết nối database từ PHP
- Mở file code `C:\xampp\htdocs\test-taynambo-main\src\config.php`
- Tiến hành hiệu chỉnh thông tin tài khoản kết nối phù hợp
```
public static $DB_CONNECTION_HOST           = 'localhost';
public static $DB_CONNECTION_USERNAME       = 'root';
public static $DB_CONNECTION_PASSWORD       = '';
public static $DB_CONNECTION_DATABASE_NAME  = 'test_taynambo';
```

# Test hệ thống
- Sử dụng trình duyệt web (Chrome, Cốc cốc, Firefox, Safari...)
- Chạy URL: http://dtmman.taynambo/