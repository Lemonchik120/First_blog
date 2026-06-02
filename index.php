<?php 
session_start(); 
include 'db.php'; 

$isAdmin = isset($_SESSION['username']);
// Прибираємо var_dump для чистого вигляду, вони заважають дизайну
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мій Блог</title>
    <!-- Додаємо шрифти -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --danger: #dc2626;
            --text: #1f2937;
            --bg: #f8fafc;
            --card: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        h1 { font-size: 2.5rem; margin: 0; font-weight: 700; color: #111827; }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            display: inline-block;
        }

        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { opacity: 0.9; }
        
        .btn-login { color: var(--primary); border: 1px solid var(--primary); }
        .btn-login:hover { background: var(--primary); color: white; }

        .post-card {
            background: var(--card);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: transform 0.2s;
        }

        .post-card:hover { transform: translateY(-5px); }

        .post-card h2 { margin-top: 0; font-size: 1.8rem; }
        .post-card h2 a { color: inherit; text-decoration: none; }
        .post-card h2 a:hover { color: var(--primary); }

        .meta { color: #6b7280; font-size: 0.9rem; margin-bottom: 15px; }

        .post-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .post-excerpt { color: #4b5563; margin-bottom: 20px; }

        .read-more { color: var(--primary); font-weight: 600; text-decoration: none; }

        .admin-actions {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .admin-actions a {
            text-decoration: none;
            font-size: 0.9rem;
            margin-right: 15px;
        }

        .delete-link { color: var(--danger); }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>Блог</h1>
        <div>
            <?php if ($isAdmin): ?>
                <span style="margin-right: 15px;">👋<?php echo ($_SESSION["username"].$_SESSION["user_id"])?></span>
                <a href="create.php" class="btn btn-primary">+ Новий пост</a>
                <a href="logout.php" style="margin-left: 10px; color: #666;">Вийти</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-login">Вхід</a>
                <a href="register.php" class="btn btn-login">Реєстрація</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <?php
        $query = "SELECT posts.*, users.username FROM posts 
                  LEFT JOIN users ON posts.user_id = users.id 
                  ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($result)): ?>
            <article class="post-card">


                <div class="meta">
                    Автор: <b><?= htmlspecialchars($row['username'] ?? 'Анонім') ?></b> • 
                    <?= date("d.m.Y", strtotime($row['created_at'])) ?>
                </div>
                  
                <h2>
                    <a href="view.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a>
                </h2>
                <?php if ($row['image']): ?>
                    <img src="uploads/<?= $row['image'] ?>" alt="image" class="post-img">
                <?php endif; ?>


                <p class="post-excerpt">
                    <?php 
                        $excerpt = mb_substr(strip_tags($row['content']), 0, 150) . "...";
                        echo nl2br(htmlspecialchars($excerpt));
                    ?>
                </p>

                <a href="view.php?id=<?= $row['id'] ?>" class="read-more">Читати далі →</a>

                <?php 
        // 1. Визначаємо, чи користувач адмін
        $is_admin = isset($_SESSION['Admin']) && $_SESSION['Admin'] === true;

        // 2. Визначаємо, чи користувач є автором поста
        $is_author = isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $row['user_id']);

            if ($is_admin || $is_author): ?>
                <div class="admin-actions">
                    <a href="edit.php?id=<?= $row['id'] ?>">📝 Редагувати</a>
                    <a href="delete.php?id=<?= $row['id'] ?>&type=post" class="delete-link" 
                    onclick="return confirm('Видалити цей пост?')">🗑️ Видалити</a>
                </div>
            <?php endif; ?>
            </article>
        <?php endwhile; ?>
    </main>
</div>

</body>
</html>