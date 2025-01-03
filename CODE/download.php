<?php
if (isset($_GET['file'])) {
    $filePath = $_GET['file'];

    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo '파일을 찾을 수 없습니다.';
    }
} else {
    echo '파일 경로가 지정되지 않았습니다.';
}
?>
