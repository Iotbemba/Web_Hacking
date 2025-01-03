<?php
require_once("db_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // POST로 전송된 데이터 받기
    $postId = $_POST["post_id"];

    // 데이터베이스에서 게시글 삭제
    $sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);

    if ($stmt->execute()) {
        header("Location: index.php"); // 삭제 성공 시 목록 페이지로 리다이렉트
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
