<?php
// 세션에서 현재 로그인한 사용자의 ID를 가져오기
$loggedInUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("db_config.php");
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
$message = ""; // 알림 메시지를 저장할 변수

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // POST로 전송된 데이터 받기
    $title = $_POST["title"];
    $content = $_POST["content"];
    
    // 파일 업로드 처리
    $targetDirectory = "uploads/"; // 파일이 저장될 디렉토리
    $targetFile = $targetDirectory . basename($_FILES["file"]["name"]);

    // 파일이 선택되었는지 확인
    if (!empty($_FILES["file"]["name"])) {
        // 파일이 선택된 경우 파일 업로드 처리
        $targetDirectory = "uploads/"; // 파일이 저장될 디렉토리
        $targetFile = $targetDirectory . basename($_FILES["file"]["name"]);

        // 파일 업로드 시도
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            // 파일 업로드 성공 시 데이터베이스에 게시글 저장
            $sql = "INSERT INTO posts (title, content, file_path, username) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $title, $content, $targetFile, $username);

            if ($stmt->execute()) {
                echo "User registered successfully";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "파일 업로드 실패";
        }
    } else {
        // 파일이 선택되지 않은 경우 데이터베이스에 게시글 저장
        $sql = "INSERT INTO posts (title, content, user_id, username) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $title, $content, $loggedInUserId, $username);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: index.php"); // index.php 페이지로 리다이렉트
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글쓰기</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
<body>
    <div class="board">
        <h1>글쓰기</h1>
        <form method="POST" action="write.php" enctype="multipart/form-data">
            <label for="title">제목:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">내용:</label>
            <textarea id="content" name="content" cols="110" rows="4" required></textarea>

            <label for="file">파일 첨부:</label>
            <input type="file" id="file" name="file">

            <input type="submit" value="작성">
        </form>
        <a href="index.php">목록으로 돌아가기</a>
    </div>
</body>
</html>