<?php
// 要刪除 Cookie，只要把它的過期時間設定為「過去的時間」(-3600秒)，瀏覽器就會自動丟掉它
setcookie("username", "", time() - 3600, "/");
setcookie("login_time", "", time() - 3600, "/");

// 登出後回到個人簡歷頁面 (符合作業規定)
header("Location: index.php");
exit;
?>