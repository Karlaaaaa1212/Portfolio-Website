<?php
// 1. 定義資料庫常數
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'resume_db'); // 確認這是你在 phpMyAdmin 建的資料庫名稱

// 2. 建立連線函式
function db_check()
{
    // 建立 MySQLi 連線
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // 處理中文亂碼問題
    $conn->set_charset("utf8mb4");

    // 檢查連線是否失敗
    if ($conn->connect_error) {
        die("資料庫連線失敗: " . $conn->connect_error);
    }
    
    return $conn;
}
?>