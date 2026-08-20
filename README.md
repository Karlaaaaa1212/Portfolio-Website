# portfolio-auth

個人履歷網站,整合完整的 PHP 會員系統。訪客看到的是靜態履歷首頁;註冊登入後,導覽列會動態切換為會員專屬介面(顯示登入時間、個人化下拉選單),並解鎖個人資料管理與密碼修改功能。

<!-- TODO: 放登入前/登入後首頁對比截圖,這是本專案最直觀的亮點 -->
<!-- ![before-after](assets/screenshot-compare.png) -->

## Features

- **會員註冊**:即時表單驗證,輸入時動態顯示各項密碼規範的通過狀態(✔/✘ 逐項提示)
- **登入 / 登出**:Cookie-based 登入狀態管理,登出即清除 Cookie 並導回首頁
- **動態首頁 UI**:同一份首頁依登入狀態渲染不同介面——訪客見 Login/Register 按鈕,會員見登入時間與「Hi, {username} ▼」下拉選單(Profile / Change Password / Logout)
- **個人資料管理**:Email、暱稱、性別、生日、自介可隨時更新;Username 與註冊日期設為唯讀
- **修改密碼**:驗證舊密碼、禁止新舊密碼相同、強制符合密碼複雜度規範

## Security Highlights

- **密碼雜湊儲存**:使用 PHP `password_hash()` / `password_verify()`,資料庫不存明文密碼
- **Prepared Statements**:所有 SQL 查詢皆使用參數綁定,防止 SQL Injection
- **前後端雙重驗證**:前端 JavaScript 即時提示 + 後端 PHP 二次防禦,繞過前端仍會被擋下
- **密碼複雜度規範**:至少 8 碼,須包含大寫、小寫、數字與特殊符號
- **輸出跳脫**:使用者資料經 `htmlspecialchars()` 處理後才輸出,防止 XSS
- **最小化 Cookie**:僅存放不具敏感性的 username 與登入時間作為登入識別

## Database Schema

`users` 資料表:

| 欄位 | 型別 | 說明 |
| --- | --- | --- |
| id | INT, PK, AUTO_INCREMENT | 會員編號 |
| username | VARCHAR(50), UNIQUE | 登入帳號(僅限英數) |
| email | VARCHAR(100), UNIQUE | 註冊信箱 |
| password | VARCHAR(255) | 雜湊後密碼 |
| reg_date | DATE | 註冊日期(自動記錄) |
| nickname / gender / birth / info | — | 選填的個人資料欄位 |

## Tech Stack

| 層級 | 技術 |
| --- | --- |
| 前端 | HTML5、CSS3(毛玻璃 + Cyberpunk 霓虹風格)、Vanilla JS |
| 後端 | PHP(mysqli + Prepared Statements) |
| 資料庫 | MySQL |
| 開發環境 | XAMPP |

## Project Structure

```
├── index.php              # 履歷首頁(依登入狀態動態渲染)
├── register.html/.php     # 註冊頁與後端處理
├── login.html/.php        # 登入頁與後端處理
├── logout.php             # 登出(清除 Cookie)
├── profile.php            # 個人資料管理
├── change_password.php    # 修改密碼
├── assets/                # 靜態資源
└── config/
    └── db_connect.php     # 資料庫連線設定
```

## Quick Start

1. 安裝 XAMPP 並啟動 Apache 與 MySQL
2. 將專案放入 `htdocs/`
3. 建立 MySQL 資料庫並依上方 schema 建立 `users` 資料表
4. 確認 `config/db_connect.php` 的連線資訊與本機環境一致
5. 瀏覽器開啟 `http://localhost/<專案資料夾>/index.php`
