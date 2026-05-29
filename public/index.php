<?php
session_start();

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controllers/MedicineController.php';
require_once __DIR__ . '/../controllers/ApiController.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if (($pos = strpos($request_uri, '?')) !== false) {
    $request_uri = substr($request_uri, 0, $pos);
}

$base_path = '/medicines/public';

if (strpos($request_uri, $base_path) === 0) {
    $request_uri = substr($request_uri, strlen($base_path));
}

if (empty($request_uri) || $request_uri == '/' || $request_uri == '/index.php') {
    $request_uri = '/';
}
$controller = new MedicineController();
$controller->decrementDays();

if ($request_uri == '/') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /medicines/public/login');
        exit;
    }
    $controller->index();
} 
elseif ($request_uri == '/login') {
    $auth = new AuthController();
    if ($request_method == 'POST') {
        $auth->login();
    } else {
        if (isset($_SESSION['user_id'])) {
            header('Location: /medicines/public/');
            exit;
        }
        $auth->showLogin();
    }
} 
elseif ($request_uri == '/register') {
    $auth = new AuthController();
    if ($request_method == 'POST') {
        $auth->register();
    } else {
        if (isset($_SESSION['user_id'])) {
            header('Location: /medicines/public/');
            exit;
        }
        $auth->showRegister();
    }
} 
elseif ($request_uri == '/logout') {
    $auth = new AuthController();
    $auth->logout();
} 
elseif ($request_uri == '/add-medicine') {
    $controller->addMedicine();
} 
elseif ($request_uri == '/add-appointment') {
    $controller->addAppointment();
} 
elseif ($request_uri == '/delete-medicine') {
    $controller->deleteMedicine();
} 
elseif ($request_uri == '/delete-appointment') {
    $controller->deleteAppointment();
} 
elseif (strpos($request_uri, '/api/') === 0) {
    $api = new ApiController();
    if ($request_uri == '/api/today-medicines') {
        $api->getTodayMedicines();
    } elseif ($request_uri == '/api/update-order') {
        $api->updateOrder();
    } elseif ($request_uri == '/api/mark-taken') {
        $api->markTaken();
    } else {
        header('HTTP/1.0 404 Not Found');
        echo 'API endpoint not found';
    }
} 
else {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>404 - Page Not Found</h1>';
    echo '<p>Requested: ' . htmlspecialchars($request_uri) . '</p>';
    echo '<p><a href="/medicines/public/">Вернуться на главную</a></p>';
}