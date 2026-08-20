<?php
if (!isset($_COOKIE['username'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.html';</script>";
    exit;
}

require_once 'config/db_connect.php';
$conn = db_check();
$current_user = $_COOKIE['username'];
$message = "";

// 處理使用者送出的更新請求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $nickname = !empty($_POST['nickname']) ? $_POST['nickname'] : null;
    $gender = !empty($_POST['gender']) ? $_POST['gender'] : null;
    $birth = !empty($_POST['birth']) ? $_POST['birth'] : null;
    $info = !empty($_POST['info']) ? $_POST['info'] : null;

    // 更新資料庫中的個人資訊
    $update_sql = "UPDATE users SET email = ?, nickname = ?, gender = ?, birth = ?, info = ? WHERE username = ?";
    $stmt = $conn->prepare($update_sql);
    
    if ($stmt) {
        $stmt->bind_param("ssssss", $email, $nickname, $gender, $birth, $info, $current_user);
        if ($stmt->execute()) {
            $message = "<p style='color: #00ff00;'>✅ Profile updated successfully!</p>";
        } else {
            $message = "<p style='color: #ff4d4d;'>❌ Update failed: " . htmlspecialchars($conn->error) . "</p>";
        }
        $stmt->close();
    }
}

// 抓取目前資料庫中的使用者資料來預先填入表單
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_user);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <style>
        /* 全域與背景設定 (同步 Register) */
        body { 
            font-family: Arial, sans-serif; 
            background-image: linear-gradient(135deg, #090b29 0%, #1f4779 100%);
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 40px 0;
        }

        .form-container { 
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid #00ffff;
            border-radius: 16px;
            padding: 40px 30px; 
            width: 400px; 
            box-shadow: 0 4px 15px rgba(0, 255, 255, 0.2);
            text-align: center;
        }

        .form-container h2 {
            font-size: 32px;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: bold;
            color: #fff;
            text-shadow: -2px 0 #ff00ff, 2px 0 #00ffff;
        }

        .form-container p { font-size: 14px; margin-bottom: 20px; }

        .field-container {
            position: relative;
            margin-bottom: 20px;
            text-align: left;
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #add7ff;
            font-weight: bold;
        }

        .required::before {
            content: "* ";
            color: #ff4d4d;
            font-size: 16px;
            vertical-align: middle;
        }

        /* 唯讀欄位特別樣式 */
        .readonly-field {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border-color: #555 !important;
            color: #888 !important;
            cursor: not-allowed;
        }

        /* 輸入框與文字區塊設計 */
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="date"],
        .form-container textarea { 
            width: 100%; 
            box-sizing: border-box;
            padding: 10px 13px; 
            background-color: rgba(255, 255, 255, 0.05);
            border: 3px solid #00ffff;
            border-radius: 8px;
            color: #ffffff;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        /* 自適應深色日曆 icon */
        .form-container input[type="date"] { color-scheme: dark; }

        /* 輸入狀態光暈 */
        .form-container input:not(.readonly-field):focus,
        .form-container textarea:focus {
            outline: none;
            border-color: #ffffff !important;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.6) !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .form-container textarea { resize: vertical; min-height: 80px; }

        /* 性別按鈕設計 (同步 Register) */
        .gender-group { display: flex; gap: 10px; justify-content: space-between; }
        .gender-option { flex: 1; cursor: pointer; position: relative; }
        .gender-option input[type="radio"] { display: none; }
        .gender-option span {
            display: block; text-align: center; padding: 9px 0; 
            background-color: rgba(255, 255, 255, 0.05); border: 3px solid #00ffff;
            border-radius: 8px; color: #ffffff; font-size: 14px; font-weight: bold; transition: all 0.2s ease;
        }
        .gender-option:hover span { border-color: #ffffff; box-shadow: 0 0 10px rgba(255, 255, 255, 0.6); background-color: rgba(255, 255, 255, 0.08); }
        .gender-option input[type="radio"]:checked + span { background-color: #00ffff; border-color: #00ffff; color: #090b29; box-shadow: 0 0 15px rgba(0, 255, 255, 0.8); }

        /* 送出按鈕設計 */
        .form-container button { 
            width: 100%; padding: 10px; background-color: #090b29; color: #ffffff;
            border: 3px solid #00ffff; border-radius: 8px; font-size: 16px; font-weight: bold;
            cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 6px rgba(0, 255, 255, 0.3); margin-top: 15px;
        }
        .form-container button:hover { background-color: #090b29; border-color: #ffffff; box-shadow: 0 4px 15px rgba(255, 255, 255, 0.6); transform: translateY(-2px); }
        .form-container button:active { background-color: #00ffff; border-color: #00ffff; color: #090b29; box-shadow: 0 0 20px rgba(0, 255, 255, 0.9); transform: translateY(1px); }

        .form-container a { color: #00ffff; text-decoration: none; font-weight: bold; transition: color 0.3s ease; }
        .form-container a:hover { color: #ff00ff; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>User Profile</h2>
    <?php echo $message; ?>

    <form action="profile.php" method="POST">
        
        <div class="field-container">
            <label>Username (Cannot be changed)</label>
            <input type="text" value="<?php echo htmlspecialchars($user_data['username']); ?>" class="readonly-field" readonly>
        </div>

        <div class="field-container">
            <label class="required">Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required autocomplete="off">
        </div>

        <div class="field-container">
            <label>Intro / Bio</label>
            <textarea name="info" placeholder="Tell us something about yourself..."><?php echo htmlspecialchars($user_data['info'] ?? ''); ?></textarea>
        </div>

        <div class="field-container">
            <label>Nickname</label>
            <input type="text" name="nickname" value="<?php echo htmlspecialchars($user_data['nickname'] ?? ''); ?>" autocomplete="off">
        </div>

        <div class="field-container">
            <label>Gender</label>
            <div class="gender-group">
                <label class="gender-option">
                    <input type="radio" name="gender" value="Male" <?php echo ($user_data['gender'] === 'Male') ? 'checked' : ''; ?>>
                    <span>Male</span>
                </label>
                <label class="gender-option">
                    <input type="radio" name="gender" value="Female" <?php echo ($user_data['gender'] === 'Female') ? 'checked' : ''; ?>>
                    <span>Female</span>
                </label>
                <label class="gender-option">
                    <input type="radio" name="gender" value="Other" <?php echo ($user_data['gender'] === 'Other') ? 'checked' : ''; ?>>
                    <span>Other</span>
                </label>
            </div>
        </div>

        <div class="field-container">
            <label>Birthday</label>
            <input type="date" name="birth" value="<?php echo htmlspecialchars($user_data['birth'] ?? ''); ?>">
        </div>

        <div class="field-container">
            <label>Registration Date</label>
            <input type="text" value="<?php echo htmlspecialchars($user_data['reg_date']); ?>" class="readonly-field" readonly>
        </div>
        
        <button type="submit">Save Changes</button>
        
    </form>
    <p style="margin-top: 20px;"><a href="index.php">Back to Homepage</a></p>
</div>

</body>
</html>