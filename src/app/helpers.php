<?php
// Include cấu hình
include_once __DIR__ . '/../config.php';

// Hàm lấy full đường dẫn trang web
if (!function_exists('siteURL')) {
    function siteURL()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        return $protocol . $domainName;
    }
}

/**
 * Hàm kiểm tra file có được người dùng upload lên serer chưa?
 * 
 * @return boolean
 */
function files_uploaded()
{

    // bail if there were no upload forms
    if (empty($_FILES))
        return false;

    // check for uploaded files
    $files = $_FILES['files']['tmp_name'];
    foreach ($files as $field_title => $temp_name) {
        if (!empty($temp_name) && is_uploaded_file($temp_name)) {
            // found one!
            return true;
        }
    }
    // return false if no files were found
    return false;
}

if (!function_exists('group_by')) {
    /**
     * Function that groups an array of associative arrays by some key.
     * 
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    function group_by($key, $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }
}

if (!function_exists('is_active')) {
    function is_active($currect_page)
    {
        $url_array =  explode('/', $_SERVER['REQUEST_URI']);
        $url = end($url_array);
        if ($currect_page == $url) {
            echo 'active'; //class name in css 
        }
    }
}