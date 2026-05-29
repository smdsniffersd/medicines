<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система управления приёмом лекарств</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>

<body>
    <div class="container">
        <header>
            <div class="header-content">
                <h1>Система управления приёмом лекарств</h1>
                <?php if (isset($_SESSION['user_name'])): ?>
                    <div class="user-info">
                        <span>Здравствуйте, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                        <a href="<?= BASE_URL ?>/logout" class="logout-btn">Выйти</a>
                    </div>
                <?php endif; ?>
            </div>
        </header>
        <main>
            <script src="<?= BASE_URL ?>/js/app.js"></script>