<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Реєстрація успішна! <a href='login.php'>Увійти</a>";
    } else {
        echo "Помилка: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head><title>Реєстрація</title></head>
<body>
    <h2>Реєстрація нового користувача</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Логін" required><br><br>
        <input type="password" name="password" placeholder="Пароль" required><br><br>
        <button type="submit">Зареєструватися</button>
    </form>
</body>
</html>