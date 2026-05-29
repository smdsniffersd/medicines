<?php
require_once __DIR__ . '/../models/Medicine.php';
require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/AuthController.php';

class MedicineController
{
    private $medicineModel;
    private $appointmentModel;
    private $userModel;
    private $auth;

    public function __construct()
    {
        $this->medicineModel = new Medicine();
        $this->appointmentModel = new Appointment();
        $this->userModel = new User();
        $this->auth = new AuthController();
    }

    public function index()
    {
        $currentUser = $this->auth->checkAuth();
        $medicines = $this->medicineModel->getTodayMedicines($currentUser['id']);
        $allMedicines = $this->medicineModel->getAll();
        $users = $this->userModel->getAll();

        ob_start();
        include __DIR__ . '/../views/medicines/index.php';
        $content = ob_get_clean();

        include __DIR__ . '/../views/layout/header.php';
        echo $content;
        include __DIR__ . '/../views/layout/footer.php';
    }

    public function addMedicine()
    {
        $this->auth->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');

            if ($name) {
                $existing = $this->medicineModel->getByName($name);
                if (!$existing) {
                    $this->medicineModel->create($name);
                }
                header('Location: /medicines/public/?success=Лекарство добавлено');
                exit;
            }
        }
        header('Location: /medicines/public/?error=Ошибка добавления лекарства');
        exit;
    }

    public function addAppointment()
    {
        $this->auth->checkAuth();
        $currentUser = $this->auth->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $medicine_id = $_POST['medicine_id'] ?? '';
            $user_id = $currentUser['id'];
            $days = (int)($_POST['days'] ?? 0);
            $dosage = $_POST['dosage'] ?? '';
            $quantity_of_day = $_POST['quantity_of_day'] ?? '';
            $time_of_use = $_POST['time_of_use'] ?? '';
            $start_date = $_POST['start_date'] ?? '';

            if (
                empty($medicine_id) || empty($days) || empty($dosage) ||
                empty($quantity_of_day) || empty($time_of_use) || empty($start_date)
            ) {
                header('Location: /medicines/public/?error=Заполните все поля');
                exit;
            }

            if ($days <= 0) {
                header('Location: /medicines/public/?error=Количество дней должно быть больше 0');
                exit;
            }

            if ($dosage <= 0) {
                header('Location: /medicines/public/?error=Дозировка должна быть больше 0');
                exit;
            }

            if ($quantity_of_day <= 0) {
                header('Location: /medicines/public/?error=Количество приёмов должно быть больше 0');
                exit;
            }

            $startDateTime = new DateTime($start_date);
            $today = new DateTime(date('Y-m-d'));
            $endDateTime = clone $startDateTime;
            $endDateTime->modify('+' . $days . ' days');
            $end_date = $endDateTime->format('Y-m-d');

            if ($startDateTime > $today) {
                header('Location: /medicines/public/?error=Дата начала не может быть в будущем');
                exit;
            }

            if ($today > $endDateTime) {
                header('Location: /medicines/public/?error=Курс приёма уже закончился! Нельзя добавить просроченное назначение.');
                exit;
            }

            $daysRemaining = $today->diff($endDateTime)->days;
            if ($daysRemaining <= 0 && $today != $endDateTime) {
                header('Location: /medicines/public/?error=Курс приёма закончился (осталось 0 дней)');
                exit;
            }

            $id = $this->appointmentModel->create(
                $medicine_id,
                $user_id,
                $end_date,
                $days,
                $dosage,
                $quantity_of_day,
                $time_of_use,
                $start_date
            );

            if ($id) {
                if ($startDateTime < $today) {
                    $missedDays = $today->diff($startDateTime)->days;
                    if ($missedDays > 0) {
                        header('Location: /medicines/public/?success=Назначение добавлено, но пропущено ' . $missedDays . ' дней');
                        exit;
                    }
                }
                header('Location: /medicines/public/?success=Назначение добавлено');
                exit;
            }
        }
        header('Location: /medicines/public/?error=Ошибка добавления назначения');
        exit;
    }

    public function deleteAppointment()
    {
        $this->auth->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            if ($id) {
                $result = $this->appointmentModel->delete($id);
                if ($result['success']) {
                    header('Location: /medicines/public/?success=Назначение удалено');
                    exit;
                }
            }
        }
        header('Location: /medicines/public/?error=Ошибка удаления назначения');
        exit;
    }

    public function deleteMedicine()
    {
        $this->auth->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            if ($id) {
                $result = $this->medicineModel->delete($id);
                if ($result['success']) {
                    header('Location: /medicines/public/?success=Лекарство удалено');
                    exit;
                }
            }
        }
        header('Location: /medicines/public/?error=Ошибка удаления лекарства');
        exit;
    }

    public function decrementDays()
    {
        if ($this->appointmentModel) {
            $this->appointmentModel->deleteExpired();
        }
    }
}
