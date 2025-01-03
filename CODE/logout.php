<?php
// 세션 시작
session_start();

// 로그아웃 버튼이 클릭된 경우
if (isset($_POST['logout'])) {
    // 모든 세션 변수 삭제
    $_SESSION = array();

    // 세션 파기
    session_destroy();

    // 로그인 페이지로 리다이렉트
    header("Location: main.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .button {
            margin: 0 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        .button.signup {
            background-color: #4CAF50;
            color: white;
        }
        .button.login {
            background-color: #008CBA;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Welcome to our Website!</h1>
    
    <div class="button-container">
        <form method="post" action="">
            <input type="submit" name="logout" value="Logout" class="button">
        </form>
    </div>
</body>
</html>
