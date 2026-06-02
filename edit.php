<?php
session_start();

// Перевірка прав доступу (використовуємо ту саму логіку, що в login.php)
if (!isset($_SESSION['Admin']) || $_SESSION['Admin'] !== true) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// 1. Отримуємо пост
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM posts WHERE id = $id");
    $post = mysqli_fetch_assoc($result);

    if (!$post) {
        die("Пост не знайдено!");
    }
} else {
    die("ID не вказано.");
}

// 2. Обробка форми
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    if (!empty($title) && !empty($content)) {
        $sql = "UPDATE posts SET title = '$title', content = '$content' WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Помилка оновлення: " . mysqli_error($conn);
        }
    } else {
        $error = "Поля не можуть бути порожніми!";
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати пост — Панель керування</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
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
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 700px;
            padding: 20px;
        }

        .editor-card {
            background: var(--card);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            border-left: 4px solid var(--primary);
            padding-left: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            color: #4b5563;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
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
            min-height: 250px;
            line-height: 1.6;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 30px;
        }

        .btn-save {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-save:hover {
            background: var(--primary-hover);
        }

        .btn-cancel {
            color: #64748b;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .btn-cancel:hover {
            color: #1e293b;
            text-decoration: underline;
        }

        .error-box {
            background: #fef2f2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fee2e2;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="editor-card">
        <h2>Редагувати публікацію</h2>

      <?php
session_start();

// Перевірка прав доступу (використовуємо ту саму логіку, що в login.php)
if (!isset($_SESSION['Admin']) || $_SESSION['Admin'] !== true) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// 1. Отримуємо пост
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM posts WHERE id = $id");
    $post = mysqli_fetch_assoc($result);

    if (!$post) {
        die("Пост не знайдено!");
    }
} else {
    die("ID не вказано.");
}

// 2. Обробка форми
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    if (!empty($title) && !empty($content)) {
        $sql = "UPDATE posts SET title = '$title', content = '$content' WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Помилка оновлення: " . mysqli_error($conn);
        }
    } else {
        $error = "Поля не можуть бути порожніми!";
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати пост — Панель керування</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
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
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 700px;
            padding: 20px;
        }

        .editor-card {
            background: var(--card);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            border-left: 4px solid var(--primary);
            padding-left: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            color: #4b5563;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
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
            min-height: 250px;
            line-height: 1.6;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 30px;
        }

        .btn-save {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-save:hover {
            background: var(--primary-hover);
        }

        .btn-cancel {
            color: #64748b;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .btn-cancel:hover {
            color: #1e293b;
            text-decoration: underline;
        }

        .error-box {
            background: #fef2f2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fee2e2;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="editor-card">
        <h2>Редагувати публікацію</h2>

        <?php if(isset($error)): ?>
            <div class="error-box"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Заголовок статті</label>
                <input type="text" id="title" name="title" 
                       value="<?= htmlspecialchars($post['title']) ?>" 
                       placeholder="Введіть цікавий заголовок..." required>
            </div>
            <label>Поточне зображення:</label><br>
                <?php if ($post['image']): ?>
                <img src="uploads/<?= $post['image'] ?>" style="max-width: 200px;"><br>
                <?php else: ?>
                    <p>Зображення відсутнє</p>
                <?php endif; ?>
        
        <label for="image">Змінити зображення (залиште порожнім, якщо не хочете змінювати):</label>
        <input type="file" id="image" name="image">

            <div class="form-group">
                <label for="content">Текст поста</label>
                <textarea id="content" name="content" 
                          placeholder="Про що будемо писати сьогодні?" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>

            <div class="actions">
                <button type="submit" class="btn-save">Зберегти зміни</button>
                <a href="index.php" class="btn-cancel">Скасувати</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>