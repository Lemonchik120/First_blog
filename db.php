<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'my_blog';

// Встановлюємо з'єднання через mysqli
$conn = mysqli_connect($host, $user, $pass, $db);

// Перевірка з'єднання
if (!$conn) {
    die("Помилка підключення: " . mysqli_connect_error());
}

// Встановлюємо кодування UTF-8
mysqli_set_charset($conn, "utf8");
?>