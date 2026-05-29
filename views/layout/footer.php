        </main>
        <footer>
            <div class="footer-content">
                <p>© 2026 Система управления приёмом лекарств</p>
                
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                    <p>Авторизован: <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></p>
                    <?php if (isset($_SESSION['user_email'])): ?>
                        <p>Email: <?= htmlspecialchars($_SESSION['user_email']) ?></p>
                    <?php endif; ?>
                <?php endif; ?>
                <hr style="margin: 10px 0; border-color: rgba(255,255,255,0.3);">
            </div>
        </footer>
    </div>
</body>
</html>