<?php
// login_process.php
header('Content-Type: application/json');
session_start();

require_once 'config.php';

// 接收 POST 資料
$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['username']) && isset($data['password'])) {
    // 清理輸入資料
    $username = mysqli_real_escape_string($con, $data['username']);
    $password = $data['password'];
    
    // 準備 SQL 查詢
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    
    if($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if(mysqli_stmt_fetch($stmt)) {
                    if(password_verify($password, $hashed_password)) {
                        // 密碼正確，建立 session
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        
                        echo json_encode([
                            "success" => true,
                            "message" => "登入成功",
                            "redirect" => "stream.php"
                        ]);
                    } else {
                        echo json_encode([
                            "success" => false,
                            "message" => "帳號或密碼錯誤"
                        ]);
                    }
                }
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "帳號或密碼錯誤"
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "系統錯誤，請稍後再試"
            ]);
        }
        mysqli_stmt_close($stmt);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "請提供帳號和密碼"
    ]);
}

mysqli_close($con);
?>
