<?php
require_once("db_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // POST로 전송된 데이터 받기
    $postId = $_POST["post_id"];
    $newContent = $_POST["new_content"];

    // 데이터베이스에서 게시글 수정
    $sql = "UPDATE posts SET content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newContent, $postId);

    if ($stmt->execute()) {
        header("Location: view.php?id=" . $postId); // 수정 성공 시 해당 게시물 페이지로 리다이렉트
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// GET으로 전송된 게시물 ID를 확인
if (isset($_GET["id"])) {
    $postId = $_GET["id"];

    // 데이터베이스에서 해당 ID의 게시물 정보 가져오기
    $sql = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    
    $stmt->close();
} else {
    echo "게시물 ID를 찾을 수 없습니다.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>글 수정</title>
</head>
<body>
    <div class="board">
        <h1>글 수정</h1>
        <form method="post" action="edit_post.php">
            <label for="new_content">새로운 내용:</label>
            <textarea id="new_content" name="new_content" cols="110" rows="4" required><?php echo $post['content']; ?></textarea>

            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <input type="submit" value="수정">
        </form>
        <a href="view.php?id=<?php echo $post['id']; ?>">취소</a>
    </div>
</body>
</html>
