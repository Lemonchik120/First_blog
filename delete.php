<?php
session_start();
include 'db.php';

// Перевірка, чи користувач авторизований
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$is_admin = isset($_SESSION['Admin']) && $_SESSION['Admin'] === true;
$id = (int)$_GET['id'];
$type = $_GET['type'] ?? 'post'; // Тип: post або comment

if ($type === 'post') {
    // Отримуємо автора поста
    $query = "SELECT user_id FROM posts WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $post = mysqli_fetch_assoc($result);

    // Дозвіл, якщо це адмін або автор
    if ($post && ($is_admin || $post['user_id'] == $user_id)) {
        mysqli_query($conn, "DELETE FROM posts WHERE id = $id");
    }
} else {
    // Аналогічно для коментарів
    $query = "SELECT user_id FROM comments WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $comment = mysqli_fetch_assoc($result);

    if ($comment && ($is_admin || $comment['user_id'] == $user_id)) {
        mysqli_query($conn, "DELETE FROM comments WHERE id = $id");
    }
}

header("Location: index.php");
exit();
?>