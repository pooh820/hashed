<?php
// config.php
define('DB_SERVER', '192.168.60.134');
define('DB_USERNAME', 'root');     // 改成你的資料庫帳號
define('DB_PASSWORD', '123456');         // 改成你的資料庫密碼
define('DB_NAME', 'player');

// 建立資料庫連線
$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// 檢查連線
if($con === false){
    die("錯誤: 無法連線到資料庫. " . mysqli_connect_error());
}

// 設定中文編碼
mysqli_query($con, "SET NAMES 'utf8mb4'");
?>
