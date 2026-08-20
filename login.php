<?php
date_default_timezone_set('Asia/Taipei');
// 1. 引入資料庫連線
require_once 'config/db_connect.php'; 
$conn = db_check();

// 2. 準備與 register.php 完全一致的 UI 渲染函式
function render_response_page($title, $message, $is_success) {
    // 依據成功與否，決定按鈕文字與跳轉連結
    $link_text = $is_success ? "Enter Website" : "Back to Login";
    $link_url = $is_success ? "index.php" : "login.html";
    $status_icon = $is_success ? "✅" : "❌";
    
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>{$title}</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                background-image: linear-gradient(135deg, #090b29 0%, #1f4779 100%);
                color: #ffffff;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .form-container { 
                background-color: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(10px);
                border: 1px solid #00ffff;
                border-radius: 16px;
                padding: 40px 30px; 
                width: 320px; 
                box-shadow: 0 4px 15px rgba(0, 255, 255, 0.2);
                text-align: center;
            }
            .form-container h2 {
                font-size: 28px;
                margin-top: 0;
                margin-bottom: 20px;
                font-weight: bold;
                color: #fff;
                text-shadow: -2px 0 #ff00ff, 2px 0 #00ffff;
            }
            .status-icon {
                font-size: 48px;
                margin-bottom: 15px;
            }
            .message-text {
                font-size: 16px;
                line-height: 1.6;
                margin-bottom: 25px;
                color: #e0e0e0;
            }
            .nav-btn { 
                display: block;
                width: 100%; 
                box-sizing: border-box;
                padding: 10px; 
                background-color: #090b29;
                color: #ffffff;
                border: 3px solid #00ffff;
                border-radius: 8px;
                font-size: 16px;
                font-weight: bold;
                text-decoration: none;
                transition: all 0.2s ease;
                box-shadow: 0 2px 6px rgba(0, 255, 255, 0.3);
            }
            .nav-btn:hover {
                background-color: #090b29;
                border-color: #ffffff;
                box-shadow: 0 4px 15px rgba(255, 255, 255, 0.6);
                transform: translateY(-2px);
                color: #ffffff;
            }
            .nav-btn:active {
                background-color: #00ffff;
                border-color: #00ffff;
                color: #090b29;
                box-shadow: 0 0 20px rgba(0, 255, 255, 0.9);
                transform: translateY(1px); 
            }
        </style>
    </head>
    <body>
    <div class='form-container'>
        <div class='status-icon'>{$status_icon}</div>
        <h2>{$title}</h2>
        <div class='message-text'>{$message}</div>
        <a href='{$link_url}' class='nav-btn'>{$link_text}</a>
    </div>
    </body>
    </html>
    ";
}

// 3. 檢查是否為 POST 送出
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // 尋找資料庫中是否有這個帳號
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        // 如果找到該帳號，開始比對密碼
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            // 使用 password_verify 比對「明文密碼」與資料庫裡的「雜湊密碼」
            if (password_verify($pass, $row['password'])) {
                
                // 不使用 Session，改用 Cookie
                setcookie("username", $row['username'], time() + 3600, "/");
                setcookie("login_time", date("Y-m-d H:i:s"), time() + 3600, "/"); 
                
                // 🌟 登入成功：顯示美化畫面，按鈕指向 index.php
                render_response_page("Login Successful", "Welcome back, " . htmlspecialchars($row['username']) . "!<br>You are successfully logged in.", true);
                exit;
                
            } else {
                // ❌ 密碼錯誤
                render_response_page("Login Failed", "Incorrect password. Please try again.", false);
            }
        } else {
            // ❌ 找不到帳號
            render_response_page("Login Failed", "Account not found. Please check your username.", false);
        }
        $stmt->close();
    } else {
        render_response_page("System Error", "Database SQL statement internal preparation failed.", false);
    }
}
$conn->close();
?>

<!-- <?php

// 1. 引入資料庫連線
require_once 'config/db_connect.php'; 
$conn = db_check();

// 2. 檢查是否為 POST 送出
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // 3. 尋找資料庫中是否有這個帳號
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        // 4. 如果找到該帳號，開始比對密碼
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            // 使用 password_verify 比對「明文密碼」與資料庫裡的「雜湊密碼」
            if (password_verify($pass, $row['password'])) {
                
                // 【修改這裡】不使用 Session，改用 Cookie！
                // 參數：(Cookie名稱, 存入的值, 過期時間(目前時間+3600秒=1小時), 路徑(/代表整個網站有效))
                setcookie("username", $row['username'], time() + 3600, "/");
                setcookie("login_time", date("Y-m-d H:i:s"), time() + 3600, "/"); // 順便記錄登入時間符合加分項
                
                // 登入成功後，重導向到首頁
                header("Location: index.php");
                exit;
                
            } else {
                echo "<h3>❌ 登入失敗：密碼錯誤！</h3>";
                echo "<a href='login.html'>重新登入</a>";
            }
        } else {
            echo "<h3>❌ 登入失敗：找不到該帳號！</h3>";
            echo "<a href='login.html'>重新登入</a>";
        }
        $stmt->close();
    }
}
$conn->close();
?> -->