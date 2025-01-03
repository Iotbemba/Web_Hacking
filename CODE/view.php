<?php
session_start();
require_once("db_config.php");

// 세션에서 현재 로그인한 사용자의 ID를 가져오기
$loggedInUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// 현재 로그인한 사용자의 ID와 글 작성자의 ID가 일치하는 경우에만 수정 및 삭제 버튼 표시
if ($loggedInUserId == $post['user_id']) {
    echo '<a href="edit_post.php?id=' . $post['id'] . '" class="edit-btn">수정</a>';
    echo '<a href="delete_post.php?id=' . $post['id'] . '" class="delete-btn">삭제</a>';
}

// URL 매개변수로부터 게시글 ID를 가져옴
if (isset($_GET["id"])) {
    $post_id = $_GET["id"];

    // 데이터베이스에서 해당 ID의 게시글을 가져옴
    $sql = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // 게시글이 존재하면 내용을 출력
        $post = $result->fetch_assoc();
        $title = $post["title"];
        $content = $post["content"];
        $created_at = $post["created_at"]; // 작성일 추가
    } else {
        // 게시글이 존재하지 않으면 오류 메시지 출력
        $error_message = "게시글을 찾을 수 없습니다.";
    }

    $stmt->close();
} else {
    // 게시글 ID가 제공되지 않으면 오류 메시지 출력
    $error_message = "게시글이 없습니다.";
}
$filePath = $post['file_path'];
if (!empty($filePath)) {
    echo "<p><a href='download.php?file=" . urlencode($filePath) . "' target='_blank'>파일 다운로드</a></p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title><?php echo $post['title']; ?></title>
</head>
<body>
    <div class="board">
        <h1><?php echo $post['title']; ?></h1>
        <table>
            <tr>
                <th>ID</th>
                <td><?php echo $post['id']; ?></td>
            </tr>
            <tr>
                <th>내용</th>
                <td style="white-space: pre-line;"><?php echo $post['content']; ?></td>
            </tr>
            <tr>
                <th>작성일</th>
                <td><?php echo $post['created_at']; ?></td>
            </tr>
        </table>
        <a href="index.php">목록으로 돌아가기</a>
        <!-- <button onclick="confirmDelete(<?php echo $post['id']; ?>)">삭제</button>
        <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="delete-btn">삭제</a> -->
        <button onclick="confirmDelete(<?php echo $post['id']; ?>)" class="delete-btn">삭제</button>
        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="edit-btn">수정</a>
    </div>

    <script>
        function confirmDelete(postId) {
            var confirmDelete = confirm('정말로 삭제하시겠습니까?');
            if (confirmDelete) {
                // 삭제 확인 시 폼 서브밋
                var form = document.createElement('form');
                form.method = 'post';
                form.action = 'delete_post.php';
                
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'post_id';
                input.value = postId;

                form.appendChild(input);
                document.body.appendChild(form);
                
                form.submit();
            }
        }
    </script>
</body>
</html>


