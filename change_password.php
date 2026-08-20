<?php
// 1. 權限檢查：必須要有 Cookie 才能訪問此頁面
if (!isset($_COOKIE['username'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.html';</script>";
    exit;
}

require_once 'config/db_connect.php';
$conn = db_check();
$current_user = $_COOKIE['username'];
$message = ""; // 用來存放射出的提示訊息

// 2. 當使用者按下「確認修改」送出表單時 (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // 檢查 A：新密碼與確認密碼是否一致
    if ($new_pass !== $confirm_pass) {
        $message = "<p style='color: #ff4d4d;'>❌ New passwords do not match!</p>";
    } 
    // 檢查 B：後端二次防禦 - 檢查新密碼是否符合安全複雜度要求
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}$/', $new_pass)) {
        $message = "<p style='color: #ff4d4d;'>❌ Password does not meet security requirements!</p>";
    } 
    else {
        // 檢查 C：將資料表名稱修正為 users
        $sql = "SELECT password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $current_user);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            // 驗證舊密碼是否輸入正確
            if (password_verify($old_pass, $row['password'])) {
                
                // 🛠️ 檢查 D：新密碼不能與舊密碼相同
                if (password_verify($new_pass, $row['password'])) {
                    $message = "<p style='color: #ff4d4d;'>❌ The new password cannot be the same as the old password!</p>";
                } else {
                    // 舊密碼正確且新密碼不重複，將新密碼進行雜湊加密
                    $hashed_new_password = password_hash($new_pass, PASSWORD_DEFAULT);

                    // 更新資料庫中的密碼 (資料表名稱修正為 users)
                    $update_sql = "UPDATE users SET password = ? WHERE username = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ss", $hashed_new_password, $current_user);

                    if ($update_stmt->execute()) {
                        $message = "<p style='color: #00ff00;'>✅ Password changed successfully!</p>";
                    } else {
                        $message = "<p style='color: #ff4d4d;'>❌ System error, update failed.</p>";
                    }
                    $update_stmt->close();
                }
            } else {
                $message = "<p style='color: #ff4d4d;'>❌ Incorrect old password!</p>";
            }
            $stmt->close();
        } else {
            // 補上錯誤捕捉機制：如果 SQL 準備失敗，顯示錯誤原因
            $message = "<p style='color: #ff4d4d;'>❌ Database Error: " . htmlspecialchars($conn->error) . "</p>";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
        <style>
            /* 全域與背景設定 */
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

            /* 表單外框 (毛玻璃效果) */
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

            /* 標題 RGB 色差風格 */
            .form-container h2 {
                font-size: 32px;
                margin-top: 0;
                margin-bottom: 25px;
                font-weight: bold;
                color: #fff;
                text-shadow: -2px 0 #ff00ff, 2px 0 #00ffff;
            }

            /* 提示訊息 */
            .form-container p {
                font-size: 14px;
                line-height: 1.5;
                margin-bottom: 20px;
            }

            /* 欄位容器 (同步 register) */
            .field-container {
                position: relative;
                margin-bottom: 20px;
                text-align: left;
            }

            /* 輸入框標籤 */
            .form-container label {
                display: block;
                text-align: left;
                margin-bottom: 8px;
                font-size: 14px;
                color: #add7ff;
                font-weight: bold;
            }

            /* 輸入框設計 */
            .form-container input { 
                width: 100%; 
                box-sizing: border-box;
                padding: 10px 13px; 
                background-color: rgba(255, 255, 255, 0.05);
                border: 3px solid #00ffff;
                border-radius: 8px;
                color: #ffffff;
                font-size: 16px;
                transition: all 0.3s ease;
            }

            /* 輸入框點擊時的光暈 */
            .form-container input:focus {
                outline: none;
                border-color: #ffffff !important;
                box-shadow: 0 0 10px rgba(255, 255, 255, 0.6) !important;
                background-color: rgba(255, 255, 255, 0.1);
            }

            /* 實時提示彈窗設定 (同步 register) */
            .tooltip {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: #000000; 
                border: 1px solid #ffffff;
                border-radius: 8px;
                padding: 12px;
                margin-top: 5px;
                z-index: 100;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
                box-sizing: border-box;
            }

            .form-container input:focus ~ .tooltip {
                display: block;
            }

            .tooltip ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .tooltip li {
                font-size: 12.5px;
                margin-bottom: 6px;
                color: #ffffff; 
                display: flex;
                align-items: center;
            }

            .tooltip li:last-child {
                margin-bottom: 0;
            }

            .tooltip li::before {
                margin-right: 8px;
                font-weight: bold;
                display: inline-block;
                width: 14px;
            }

            .tooltip li.invalid::before {
                content: "✘";
                color: #ff4d4d; 
            }

            .tooltip li.valid::before {
                content: "✔";
                color: #00ff00; 
            }

            /* 按鈕設計 */
            .form-container button { 
                width: 100%; 
                padding: 10px; 
                background-color: #090b29;
                color: #ffffff;
                border: 3px solid #00ffff;
                border-radius: 8px;
                font-size: 16px;
                font-weight: bold;
                cursor: pointer; 
                transition: all 0.2s ease;
                box-shadow: 0 2px 6px rgba(0, 255, 255, 0.3);
                margin-top: 10px;
                box-sizing: border-box;
            }

            .form-container button:hover {
                background-color: #090b29;
                border-color: #ffffff;
                box-shadow: 0 4px 15px rgba(255, 255, 255, 0.6);
                transform: translateY(-2px);
                color: #ffffff;
            }

            .form-container button:active {
                background-color: #00ffff;
                border-color: #00ffff;
                color: #090b29;
                box-shadow: 0 0 20px rgba(0, 255, 255, 0.9);
                transform: translateY(1px); 
            }

            /* 底部連結設計 */
            .form-container a {
                color: #00ffff;
                text-decoration: none;
                font-weight: bold;
                transition: color 0.3s ease;
            }

            .form-container a:hover {
                color: #ff00ff;
            }
        </style>
</head>
<body>

<div class="form-container">
    <h2>Change Password</h2>
    <p>Logged in as: <strong><?php echo htmlspecialchars($current_user); ?></strong></p>
    
    <?php echo $message; ?>

    <form action="change_password.php" method="POST" id="changePasswordForm">
        
        <div class="field-container">
            <label>Old Password:</label>
            <input type="password" name="old_password" id="old_password" required autocomplete="off">
        </div>
        
        <div class="field-container">
            <label>New Password:</label>
            <input type="password" name="new_password" id="new_password" required autocomplete="off">
            <div class="tooltip">
                <ul>
                    <li id="p-length" class="invalid">At least 8 characters</li>
                    <li id="p-letter" class="invalid">Contains alphabet letters</li>
                    <li id="p-uppercase" class="invalid">Contains uppercase letter</li>
                    <li id="p-number" class="invalid">Contains number</li>
                    <li id="p-special" class="invalid">Contains special character</li>
                </ul>
            </div>
        </div>
        
        <div class="field-container">
            <label>Confirm New Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required autocomplete="off">
            <div class="tooltip">
                <ul>
                    <li id="cp-match" class="invalid">Passwords must match</li>
                </ul>
            </div>
        </div>
        
        <button type="submit">Confirm Change</button>
        
    </form>
    <br>
    <a href="index.php">Back to Homepage</a>
</div>

<script>
    const oldPasswordInput = document.getElementById('old_password');
    const newPasswordInput = document.getElementById('new_password');
    const confirmInput = document.getElementById('confirm_password');
    const form = document.getElementById('changePasswordForm');

    // New Password 實時動態驗證
    newPasswordInput.addEventListener('input', () => {
        const val = newPasswordInput.value;
        
        document.getElementById('p-length').className = val.length >= 8 ? 'valid' : 'invalid';
        document.getElementById('p-letter').className = /[a-zA-Z]/.test(val) ? 'valid' : 'invalid';
        document.getElementById('p-uppercase').className = /[A-Z]/.test(val) ? 'valid' : 'invalid';
        document.getElementById('p-number').className = /[0-9]/.test(val) ? 'valid' : 'invalid';
        document.getElementById('p-special').className = /[^a-zA-Z0-9]/.test(val) ? 'valid' : 'invalid';
        
        validateConfirm();
    });

    // Confirm Password 實時動態驗證
    confirmInput.addEventListener('input', validateConfirm);

    function validateConfirm() {
        const pVal = newPasswordInput.value;
        const cVal = confirmInput.value;
        const cpMatch = document.getElementById('cp-match');

        if (pVal === cVal && cVal.length > 0) {
            cpMatch.className = 'valid';
        } else {
            cpMatch.className = 'invalid';
        }
    }

    // 表單送出前的最終防呆阻擋
    form.addEventListener('submit', (e) => {
        const oVal = oldPasswordInput.value;
        const pVal = newPasswordInput.value;
        const cVal = confirmInput.value;

        // 🛠️ 檢查新密碼是否與舊密碼相同 (前端防護)
        if (oVal === pVal && oVal.length > 0) {
            e.preventDefault();
            alert('Change Failed: The new password cannot be the same as the old password!');
            newPasswordInput.focus();
            return;
        }

        const isPasswordValid = pVal.length >= 8 && 
                                /[a-zA-Z]/.test(pVal) && 
                                /[A-Z]/.test(pVal) && 
                                /[0-9]/.test(pVal) && 
                                /[^a-zA-Z0-9]/.test(pVal);
        const isConfirmValid = (pVal === cVal) && cVal.length > 0;

        if (!isPasswordValid) {
            e.preventDefault();
            alert('Change Failed: New password does not meet the safety requirements.');
            newPasswordInput.focus();
            return;
        }

        if (!isConfirmValid) {
            e.preventDefault();
            alert('Change Failed: Passwords do not match!');
            confirmInput.focus();
            return;
        }
    });
</script>

</body>
</html>