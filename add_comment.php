<?php
session_start();
require_once 'db.php'; // Припускаємо, що тут лежить $conn = mysqli_connect(...)

// 1. Перевірка авторизації
if (!isset($_SESSION['user_id'])) {
    exit("Помилка: доступ заборонено.");
}

// 2. Обробка даних форми
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Отримуємо дані та приводимо до безпечного типу
    $user_id = (int)$_SESSION['user_id'];
    $post_id = (int)$_POST['post_id'];
    
    // Екрануємо символи, щоб уникнути помилок в SQL (базовий захист)
    $content = mysqli_real_escape_string($conn, trim($_POST['content']));

    // 3. Перевірка на порожній коментар
    if (!empty($content) && $post_id > 0) {
        
        // Формуємо запит
        $sql = "INSERT INTO comments (post_id, user_id, content, created_at) 
                VALUES ($post_id, $user_id, '$content', NOW())";

        if (mysqli_query($conn, $sql)) {
            // 4. Успіх: повертаємося на сторінку статті
            header("Location: view.php?id=" . $post_id);
            exit();
        } else {
            echo "Помилка бази даних: " . mysqli_error($conn);
        }

    } else {
        echo "Коментар не може бути порожнім.";
    }
} else {
    // Якщо зайшли на файл просто за посиланням
    header("Location: index.php");
    exit();
}