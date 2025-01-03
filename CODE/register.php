<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
$username = mysqli_real_escape_string($conn, $_POST["username"]);
$raw_password = $_POST["password"]; // 원시 비밀번호
$phone = mysqli_real_escape_string($conn, $_POST["phone"]);
$email = mysqli_real_escape_string($conn, $_POST["email"]);

// 비밀번호를 안전하게 해싱
$password = password_hash($raw_password, PASSWORD_DEFAULT);

// 데이터베이스에 사용자 정보 저장 (준비된 문 사용)
$sql = "INSERT INTO user_info (username, password, phone, email) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssss", $username, $password, $phone, $email);
    if ($stmt->execute()) {
        echo "회원가입이 완료되었습니다.";
    } else {
        echo "오류: " . $sql . "<br>" . $conn->error;
    }
    $stmt->close();
} else {
    echo "문 준비 중 오류 발생";
}

$conn->close();
?>
