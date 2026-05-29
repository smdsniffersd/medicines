<?php
require_once __DIR__ . '/../config.php';

class User {
    
    public function getAll() {
        $sql = "SELECT id, name, last_name, email FROM users ORDER BY name";
        return AllFetch($sql);
    }
    
    public function getById($id) {
        $sql = "SELECT id, name, last_name, email FROM users WHERE id = :id";
        return OneFetch($sql, ['id' => $id]);
    }
    
    public function getByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        return OneFetch($sql, ['email' => $email]);
    }
    
    public function create($name, $last_name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $data = [
            'name' => $name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $hashedPassword
        ];
        return InsertRow('users', $data);
    }
    
    public function authenticate($email, $password) {
        $user = $this->getByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function getCurrentUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            return $this->getById($_SESSION['user_id']);
        }
        return null;
    }
}
?>