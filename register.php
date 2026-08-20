<?php
require_once 'config/db_connect.php';
$conn = db_check();

// 用來包裝 HTML 樣式的函式，讓成功與失敗畫面都擁有完整的 Cyberpunk 毛玻璃 CSS
function render_response_page($title, $message, $is_success) {
    $link_text = $is_success ? "Go to Login Page" : "Back to Register";
    $link_url = $is_success ? "login.html" : "register.html";
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];
    
    // 接收選填欄位 (若未填寫則給予空值或 null)
    $birthday = !empty($_POST['birthday']) ? $_POST['birthday'] : null;
    $gender = !empty($_POST['gender']) ? $_POST['gender'] : null;
    $nickname = !empty($_POST['nickname']) ? $_POST['nickname'] : null;

    // 🛠️ 新增：後端二次防禦 - 檢查生日是否晚於今天
    if ($birthday !== null) {
        $today_date = date("Y-m-d"); // 取得伺服器當下的 YYYY-MM-DD
        if ($birthday > $today_date) {
            render_response_page("Registration Failed", "Birthday cannot be a future date.", false);
            exit;
        }
    }
    

    // 後端二次防禦：檢查密碼與確認密碼是否一致
    if ($pass !== $confirm_pass) {
        render_response_page("Registration Failed", "Passwords do not match. Please verify your password input again.", false);
        exit;
    }

    // 後端二次防禦：檢查密碼複雜度安全性
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}$/', $pass)) {
        render_response_page("Registration Failed", "Password does not meet the security requirements.", false);
        exit;
    }

    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // 準備 SQL 語法，將必填與選填的所有欄位同步寫入資料庫
    $sql = "INSERT INTO users (username, email, password, birth, gender, nickname) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // 綁定 6 個字串參數 ("ssssss")
        $stmt->bind_param("ssssss", $user, $email, $hashed_password, $birthday, $gender, $nickname);

        if ($stmt->execute()) {
            // 🌟 註冊成功：渲染美化後的毛玻璃回應頁面
            render_response_page("Registration Successful", "Your account has been created successfully. You can now use your credentials to log in.", true);
        } else {
            // 註冊失敗 (例如帳號或 Email 重複)
            render_response_page("Registration Failed", "The username or email has already been taken. Error: " . $conn->error, false);
        }
        $stmt->close();
    } else {
        render_response_page("System Error", "Database SQL statement internal preparation failed.", false);
    }
}

$conn->close();
?>