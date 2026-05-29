<div class="auth-container">
    <div class="auth-box">
        <h2>Регистрация</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Имя:</label>
                    <input type="text" id="name" name="name" required placeholder="Иван">
                </div>
                
                <div class="form-group">
                    <label for="last_name">Фамилия:</label>
                    <input type="text" id="last_name" name="last_name" required placeholder="Петров">
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="ivan@example.com">
            </div>
            
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required placeholder="минимум 6 символов">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Подтверждение пароля:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn-primary">Зарегистрироваться</button>
        </form>
        
        <p class="auth-link">
            Уже есть аккаунт? <a href="<?= BASE_URL ?>/login">Войти</a>
        </p>
    </div>
</div>