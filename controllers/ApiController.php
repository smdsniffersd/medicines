<?php
require_once __DIR__ . '/../models/Medicine.php';
require_once __DIR__ . '/../models/Appointment.php';

class ApiController {
    private $medicineModel;
    private $appointmentModel;
    
    public function __construct() {
        $this->medicineModel = new Medicine();
        $this->appointmentModel = new Appointment();
        header('Content-Type: application/json');
    }
    
    public function getTodayMedicines() {
        $medicines = $this->medicineModel->getTodayMedicines();
        echo json_encode(['success' => true, 'data' => $medicines]);
    }
    
    public function updateOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $order = $input['order'] ?? [];
            $date = $input['date'] ?? date('Y-m-d');
            
            error_log("Order updated for date {$date}: " . json_encode($order));
            
            echo json_encode(['success' => true, 'message' => 'Порядок обновлён']);
        }
    }
    
    public function markTaken() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? '';
            $taken = $input['taken'] ?? false;
            $date = $input['date'] ?? date('Y-m-d');
            
            error_log("Medicine {$id} marked as " . ($taken ? 'taken' : 'not taken') . " on {$date}");
            
            echo json_encode(['success' => true, 'message' => 'Статус обновлён']);
        }
    }
    
    public function decrementDays() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->appointmentModel->decrementDays();
            echo json_encode(['success' => true, 'message' => 'Дни обновлены']);
        }
    }
}
?>