<div class="medicines-container">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <div class="section">
        <h2>Лекарства на сегодня</h2>
        <div class="medicines-list" id="medicinesList">
            <?php if (empty($medicines)): ?>
                <div class="empty-state">Нет назначенных лекарств на сегодня</div>
            <?php else: ?>
                <?php foreach ($medicines as $index => $medicine): ?>
                    <div class="medicine-item" data-id="<?= $medicine['id'] ?>" data-order="<?= $index ?>">
                        <div class="drag-handle">⋮⋮</div>
                        <div class="order-number"><?= $index + 1 ?></div>
                        <div class="medicine-info">
                            <strong><?= htmlspecialchars($medicine['medicine_name']) ?></strong>
                            <div class="medicine-details">
                                <span>Дозировка: <?= htmlspecialchars($medicine['dosage']) ?> мг</span>
                                <span>Приёмов в день: <?= htmlspecialchars($medicine['quantity_of_day']) ?></span>
                                <span>Относительно еды: <?= htmlspecialchars($medicine['time_of_use']) ?></span>
                                <span>Дата начала: <?= date('d.m.Y', strtotime($medicine['start_date'])) ?></span>
                                <span>Осталось дней: <?= htmlspecialchars($medicine['days_remaining']) ?></span>
                                <span>Пациент: <?= htmlspecialchars($medicine['user_name']) ?> <?= htmlspecialchars($medicine['last_name']) ?></span>
                            </div>
                        </div>
                        <div class="medicine-actions">
                            <button class="btn-taken" data-id="<?= $medicine['id'] ?>">
                                <span class="taken-status">❌ Не принято</span>
                            </button>
                            <form method="POST" action="<?= BASE_URL ?>/delete-appointment" class="inline-form" onsubmit="return confirm('Удалить назначение?')">
                                <input type="hidden" name="id" value="<?= $medicine['id'] ?>">
                                <button type="submit" class="btn-delete">🗑️</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="section">
        <h2>Статистика сегодняшних приёмов</h2>
        <div class="statistics">
            <p>Всего лекарств: <strong id="totalCount"><?= count($medicines) ?></strong></p>
            <p>Принято: <strong id="takenCount">0</strong></p>
            <p>Осталось принять: <strong id="remainingCount">0</strong></p>
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill">0%</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Назначить приём лекарства</h2>
        <form method="POST" action="<?= BASE_URL ?>/add-appointment" class="form">
            <div class="form-group" id ="CreateNewMedicine" >
                <label for="medicine_id">Лекарство:</label>
                <select id="medicine_id" name="medicine_id" required>
                    <option value="">Выберите лекарство</option>
                    <?php foreach ($allMedicines as $medicine): ?>
                        <option value="<?= $medicine['id'] ?>"><?= htmlspecialchars($medicine['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="start_date">Дата начала приёма:</label>
                    <input type="date" id="start_date" name="start_date" required value="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group">
                    <label for="days">Количество дней:</label>
                    <input type="number" id="days" name="days" step="1" min="1" required placeholder="7">
                </div>
                <div class="form-group">
                    <label for="dosage">Дозировка (мг):</label>
                    <input type="number" id="dosage" name="dosage" step="0.01" min="0.01" required placeholder="500">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="quantity_of_day">Приёмов в день:</label>
                    <input type="number" id="quantity_of_day" name="quantity_of_day" step="1" min="1" required placeholder="2">
                </div>
                <div class="form-group">
                    <label for="time_of_use">Относительно еды:</label>
                    <select id="time_of_use" name="time_of_use" required>
                        <option value="до еды">До еды</option>
                        <option value="во время еды">Во время еды</option>
                        <option value="после еды">После еды</option>
                        <option value="не зависит от еды">Не зависит от еды</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-primary">Назначить приём</button>
        </form>
    </div>

    <div class="section">
        <h2>Управление лекарствами</h2>
        <button type="button" id="showAddMedicineBtn" class="btn-primary">Добавить новое лекарство</button>
    </div>
</div>

<div id="addMedicineModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Добавить новое лекарство</h3>
            <span class="close">&times;</span>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/add-medicine" class="form" id="fromAddMedicine">
            <div class="form-group">
                <label for="medicine_name">Название лекарства:</label>
                <input type="text" id="medicine_name" name="name" required placeholder="Например: Аспирин">
            </div>
            <button type="submit" class="btn-primary">Сохранить</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('addMedicineModal');
        const btn = document.getElementById('showAddMedicineBtn');
        const closeBtn = document.querySelector('.close');

        if (btn) {
            btn.onclick = function() {
                modal.style.display = 'block';
            }
        }

        if (closeBtn) {
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    });
</script>