<?php
// 세션 시작
session_start();

// 로그인 성공 시 이전 페이지 URL을 세션에 저장
$_SESSION['prev_page'] = $_SERVER['HTTP_REFERER'];
// 데이터베이스 연결 설정
$db_host = "localhost";
$db_username = "aaa";
$db_password = "aaa";
$db_name = "user_info";

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// POST로 전송된 데이터 받기
$login_username = mysqli_real_escape_string($conn, $_POST["login_username"]);
$login_password = $_POST["login_password"];

// 데이터베이스에서 사용자 정보 조회
$login_query = "SELECT * FROM user_info WHERE username='$login_username'";
$login_result = $conn->query($login_query);

if ($login_result->num_rows > 0) {
    $row = $login_result->fetch_assoc();
    $stored_password = $row["password"];

    // 비밀번호 일치 확인
    if (password_verify($login_password, $stored_password)) {
        // 로그인 성공 시 세션에 사용자 ID 저장
        $_SESSION['user_id'] = $row['id'];

        // 이전 페이지 URL이 세션에 저장되어 있다면 해당 페이지로 리디렉션을 JavaScript로 처리
        if (isset($_SESSION['prev_page'])) {
            echo '<script>';
            echo 'alert("로그인 성공");';
            echo 'window.location.href = "' . $_SESSION['prev_page'] . '";';
            echo '</script>';
            exit();
        }

    } else {
        echo "비밀번호가 일치하지 않습니다.";
    }

} else {
    echo "사용자를 찾을 수 없습니다.";
}

$conn->close();
?>
