<?php
// 세션 시작
session_start();

// 사용자가 로그인 상태인지 확인
$is_logged_in = isset($_SESSION['user_id']);


// 사용자가 로그인 상태인 경우 JavaScript를 이용하여 로그아웃 기능 구현
if ($is_logged_in) {
    echo '<script>
            document.getElementById("logoutBtn").addEventListener("click", function() {
                // 세션 파기 요청을 서버로 보냄
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "logout.php", true);
                xhr.send();

                // 로그아웃 후 메인 페이지로 리디렉션
                window.location.href = "main.php";
            });
        </script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>민원 목록</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="button-container">
        <?php
        // 로그인 상태 확인 후 로그인/로그아웃 버튼 표시
        if ($is_logged_in) {
            echo '<a href="logout.php" class="button logout">Logout</a>';
        } else {
            echo '<a href="login.html" class="button login">Login</a>';
        }
        ?>
        <!-- <a href="register.html" class="button signup">Sign Up</a>
        <a href="login.html" class="button login">Login</a> -->
    </div>

    <h1>Welcome to our Website!</h1>
    
    <div class="main-buttons">
        <a href="page.php">소개</a>
        <a href="index.php">게시판</a>
    </div>
    <img src="company_image.jpg" alt="Company Image" style="width: 100%; max-width: 800px; margin: 20px auto; display: block;">
</body>
</html>
