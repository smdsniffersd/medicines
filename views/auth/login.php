<div class="auth-container">
    <div class="auth-box">
        <h2>Вход в систему</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        
        <form method="POST"  class="form">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="ivan@example.com">
            </div>
            
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required placeholder="••••••">
            </div>
            
            <button type="submit" class="btn-primary">Войти</button>
        </form>
        
        <p class="auth-link">
            Нет аккаунта? <a href="<?= BASE_URL ?>/register">Зарегистрироваться</a>
        </p>
    </div>
</div>

<style>
.auth-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 70vh;
}

.auth-box {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}

.auth-box h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.auth-link {
    text-align: center;
    margin-top: 20px;
}

.auth-link a {
    color: #667eea;
    text-decoration: none;
}

.auth-link a:hover {
    text-decoration: underline;
}
</style>