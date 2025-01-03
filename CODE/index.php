<?php
session_start();

// 사용자가 로그인 상태인지 확인
$is_logged_in = isset($_SESSION['user_id']);

require_once("db_config.php");

// 세션에서 현재 로그인한 사용자의 ID를 가져오기
$loggedInUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// 데이터베이스에서 모든 게시글 가져오기
$sql = "SELECT * FROM posts";
$result = $conn->query($sql);

$posts = [];


if ($result->num_rows > 0) {
    // 결과를 연관 배열로 변환
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

// foreach ($posts as $post) {
//     echo '<tr>';
//     echo '<td>' . $post['id'] . '</td>';
//     echo '<td><a href="view.php?id=' . $post['id'] . '">' . $post['title'] . '</a></td>';
//     echo '<td>' . $post['created_at'] . '</td>';
    
//     // 현재 로그인한 사용자의 ID와 글 작성자의 ID가 일치하는 경우에만 수정 및 삭제 버튼 표시
//     if ($loggedInUserId == $post['user_id']) {
//         echo '<td><a href="edit_post.php?id=' . $post['id'] . '">수정</a></td>';
//         echo '<td><a href="delete_post.php?id=' . $post['id'] . '">삭제</a></td>';
//     } else {
//         echo '<td></td>';
//         echo '<td></td>';
//     }
    
//     echo '</tr>';
// }



// 게시글 삭제 처리
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete_id"])) {
    $delete_id = $_GET["delete_id"];

    // 데이터베이스에서 해당 ID의 게시글 삭제
    $delete_sql = "DELETE FROM posts WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $delete_id);

    if ($delete_stmt->execute()) {
        // 삭제 성공 시 메시지 출력
        echo "<script>alert('게시글이 삭제되었습니다.');</script>";
    } else {
        // 삭제 실패 시 메시지 출력
        echo "<script>alert('게시글 삭제에 실패했습니다.');</script>";
    }

    $delete_stmt->close();

    // 삭제 후 index.php로 리다이렉트
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
}

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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>민원 목록</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="button-container">
        <a href="main.php" class="button">Home</a>
        <?php
        // 로그인 상태 확인 후 로그인/로그아웃 버튼 표시
        if ($is_logged_in) {
            echo '<a href="logout.php" class="button logout">Logout</a>';
        } else {
            echo '<a href="login.html" class="button login">login</a>';
        }
        ?>
    </div>
    <div class="board">
        <h1>민원 목록</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>답변</th>
                    <!-- <th>삭제</th> -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?php echo $post['id']; ?></td>
                        <td><a href="view.php?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></td>
                        <td><?php echo $post['user_id']; ?></td>
                        <td><?php echo $post['answer']; ?></td>
                        <!-- <td><a href="index.php?delete_id=<?php echo $post['id']; ?>" onclick="return confirm('정말로 삭제하시겠습니까?')">삭제</a></td> -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a href="write.php" class="button">글쓰기</a>
    </div>
</body>
</html>
