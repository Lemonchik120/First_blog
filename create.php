<?php
session_start();

// Перевірка авторизації
if (!isset($_SESSION['username'])) { 
    header("Location: login.php"); 
    exit(); 
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image_name = null;

    // Обробка завантаження файлу
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        $target = "uploads/" . $image_name;
        
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $error = "Помилка при завантаженні фото.";
        }
    }

    if (!isset($_SESSION['user_id'])) {
        die("Помилка сесії: ID користувача не знайдено.");
    }

    $user_id = $_SESSION['user_id'];
    
    if (!isset($error)) {
        $sql = "INSERT INTO posts (title, content, image, user_id) 
                VALUES ('$title', '$content', '$image_name', '$user_id')";
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Помилка бази даних: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Створити новий пост</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #1f2937;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            display: flex;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            width: 100%;
            max-width: 700px;
        }

        .header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .back-link {
            text-decoration: none;
            color: #64748b;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--text); }

        .form-card {
            background: var(--card);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #4b5563;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            box-sizing: border-box;
            transition: all 0.2s;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 200px;
            line-height: 1.6;
        }

        .file-upload-info {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 5px;
        }

        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
        }

        .error-msg {
            background: #fef2f2;
            color: #dc2626;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #fee2e2;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Новий допис</h1>
        <a href="index.php" class="back-link">← Скасувати</a>
    </div>

    <div class="form-card">
        <?php if(isset($error)): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Заголовок</label>
                <input type="text" id="title" name="title" placeholder="Про що ви думаєте?" required autofocus>
            </div>

            <div class="form-group">
                <label for="content">Текст поста</label>
                <textarea id="content" name="content" placeholder="Напишіть щось цікаве..."></textarea>
            </div>

            <div class="form-group">
                <label for="image">Обкладинка (опціонально)</label>
                <input type="file" id="image" name="image" accept="image/*">
                <div class="file-upload-info">Формати: JPG, PNG, GIF. Макс. розмір 2MB.</div>
            </div>

            <button type="submit" class="btn-submit">Опублікувати пост</button>
        </form>
    </div>
</div>

</body>
</html>