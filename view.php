<?php
include 'db.php';
session_start();
echo ($_SESSION["username"].$_SESSION["user_id"]);

// Отримуємо ID з URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Запит на отримання конкретного поста
    $sql = "SELECT * FROM posts WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $post = mysqli_fetch_assoc($result);

    if (!$post) {
        die("Статтю не знайдено!");
    }
} else {
    die("ID статті не вказано.");
}
?>


<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; }
        img { max-width: 100%; height: auto; border-radius: 8px; }
        .back-link { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <a href="index.php" class="back-link">← Назад до списку</a>

    <article>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p><small>Опубліковано: <?php echo $post['created_at']; ?></small></p>

        <?php if ($post['image']): ?>
            <img src="uploads/<?php echo $post['image']; ?>" alt="Зображення до статті">
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
        </div>
    </article>
   
    
<hr>
<h3>Коментарі</h3>
// ... (початок файлу залишається без змін)

<h3>Коментарі</h3>

<?php
// Отримуємо коментарі
$comment_query = "SELECT comments.*, users.username 
                  FROM comments 
                  JOIN users ON comments.user_id = users.id 
                  WHERE post_id = $id 
                  ORDER BY created_at DESC";
$comments_res = mysqli_query($conn, $comment_query);




if (mysqli_num_rows($comments_res) > 0) {
    while ($comment = mysqli_fetch_assoc($comments_res)) {
        echo "<div style='background: #f4f4f4; padding: 10px; margin-bottom: 10px; border-radius: 5px;'>";
        echo "<strong>" . htmlspecialchars($comment['username']) . "</strong> ";
        echo "<small style='color: gray;'>" . $comment['created_at'] . "</small>";

        // --- ЛОГІКА ВІДОБРАЖЕННЯ КНОПКИ ---
        // Кнопка з'явиться, якщо:
        // 1. Користувач авторизований
        // 2. Він або Адмін, або Автор цього коментаря (comment['user_id'])
        if (isset($_SESSION['user_id']) && (isset($_SESSION['Admin']) || $_SESSION['user_id'] == $comment['user_id'])) {
            echo " | <a href='delete.php?id=" . $comment['id'] . "&type=comment&post_id=" . $id . "' 
                     style='color: red; text-decoration: none;' 
                     onclick='return confirm(\"Видалити цей коментар?\")'>🗑️ Видалити</a>";
        }
        // ----------------------------------

        echo "<br>" . nl2br(htmlspecialchars($comment['content']));
        echo "</div>";
    }
} else {
    echo "<p>Коментарів поки немає.</p>";
}

// 2. Форма для додавання коментаря (доступна тільки авторизованим)
if (isset($_SESSION['user_id'])): ?>
    <h4>Залишити коментар:</h4>
    <form action="add_comment.php" method="POST">
        <input type="hidden" name="post_id" value="<?php echo $id; ?>">
        <textarea name="content" rows="3" style="width: 100%;" placeholder="Ваш коментар..." required></textarea><br><br>
        <button type="submit">Надіслати</button>
    </form>
<?php else: ?>
    <p><a href="login.php">Увійдіть</a>, щоб залишити коментар.</p>
<?php endif; ?>
</body>
</html>
