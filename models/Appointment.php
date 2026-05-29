<?php
require_once __DIR__ . '/../config.php';

class Appointment {

    
    public function getAll() {
        $sql = "SELECT a.*, m.name as medicine_name, u.name as user_name, u.last_name 
                FROM appointments a
                JOIN medicines m ON a.medicine_id = m.id
                JOIN users u ON a.user_id = u.id";
        return AllFetch($sql);
    }
    
    public function getByUser($userId) {
        $sql = "SELECT a.*, m.name as medicine_name 
                FROM appointments a
                JOIN medicines m ON a.medicine_id = m.id
                WHERE a.user_id = :user_id";
        return AllFetch($sql, ['user_id' => $userId]);
    }
    
    public function getById($id) {
        $sql = "SELECT a.*, m.name as medicine_name 
                FROM appointments a
                JOIN medicines m ON a.medicine_id = m.id
                WHERE a.id = :id";
        return OneFetch($sql, ['id' => $id]);
    }
    
    public function create($medicine_id, $user_id, $end_date, $days, $dosage, $quantity_of_day, $time_of_use, $start_date) {
        $data = [
            'medicine_id' => $medicine_id,
            'user_id' => $user_id,
            'end_date' => $end_date,
            'dosage' => $dosage,
            'quantity_of_day' => $quantity_of_day,
            'time_of_use' => $time_of_use,
            'start_date' => $start_date,
            'days' => $days
        ];
        return InsertRow('appointments', $data);
    }
    
    public function delete($id) {
        return deleteRow('appointments', 'id', $id);
    }
    
    public function deleteExpired() {
        $sql = "DELETE FROM appointments 
                WHERE DATE_ADD(start_date, INTERVAL days DAY) < CURDATE()";
        return execu($sql);
    }
    
    public function getRemainingDays($appointmentId) {
        $sql = "SELECT GREATEST(0, days - DATEDIFF(CURDATE(), start_date)) as days_remaining
                FROM appointments 
                WHERE id = :id";
        $result = OneFetch($sql, ['id' => $appointmentId]);
        return $result ? $result['days_remaining'] : 0;
    }
}
?>