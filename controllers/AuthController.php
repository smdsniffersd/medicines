<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function showLogin() {
        ob_start();
        include __DIR__ . '/../views/auth/login.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout/auth_header.php';
        echo $content;
        include __DIR__ . '/../views/layout/footer.php';
    }
    
    public function showRegister() {
        ob_start();
        include __DIR__ . '/../views/auth/register.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout/auth_header.php';
        echo $content;
        include __DIR__ . '/../views/layout/footer.php';
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->userModel->authenticate($email, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email']; // Добавляем email в сессию
                header('Location: /medicines/public/');
                exit;
            } else {
                header('Location: /medicines/public/login?error=Неверный email или пароль');
                exit;
            }
        }
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            $errors = [];
            
            if (empty($name)) $errors[] = 'Имя обязательно';
            if (empty($last_name)) $errors[] = 'Фамилия обязательна';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Неверный формат email';
            if (strlen($password) < 6) $errors[] = 'Пароль должен быть не менее 6 символов';
            if ($password !== $confirm_password) $errors[] = 'Пароли не совпадают';
            
            if ($this->userModel->getByEmail($email)) {
                $errors[] = 'Пользователь с таким email уже существует';
            }
            
            if (empty($errors)) {
                $userId = $this->userModel->create($name, $last_name, $email, $password);
                if ($userId) {
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_name'] = $name . ' ' . $last_name;
                    $_SESSION['user_email'] = $email;
                    header('Location: /medicines/public/');
                    exit;
                }
            }
            
            $errorString = implode(', ', $errors);
            header("Location: /medicines/public/register?error=" . urlencode($errorString));
            exit;
        }
    }
    
    public function logout() {
        session_destroy();
        header('Location: /medicines/public/login');
        exit;
    }
    
    public function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /medicines/public/login');
            exit;
        }
        return $this->userModel->getCurrentUser();
    }
}