<?php
require_once __DIR__ . '/../config.php';

class Medicine
{


    public function getAll()
    {
        $sql = "SELECT * FROM medicines ORDER BY name";
        return AllFetch($sql);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM medicines WHERE id = :id";
        return OneFetch($sql, ['id' => $id]);
    }

    public function create($name)
    {
        $data = ['name' => $name];
        return InsertRow('medicines', $data);
    }

    public function delete($id)
    {
        return deleteRow('medicines', 'id', $id);
    }

    public function getTodayMedicines($userId = null)
    {
        if (!$userId && session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = $userId ?? ($_SESSION['user_id'] ?? 0);

        $sql = "SELECT a.*, m.name as medicine_name, 
                       u.name as user_name, u.last_name,
                       DATEDIFF(CURDATE(), a.start_date) as days_passed,
                       GREATEST(0, a.days - DATEDIFF(CURDATE(), a.start_date)) as days_remaining
                FROM appointments a
                JOIN medicines m ON a.medicine_id = m.id
                JOIN users u ON a.user_id = u.id
                WHERE a.user_id = :user_id
                AND a.start_date <= CURDATE() 
                AND DATE_ADD(a.start_date, INTERVAL a.days DAY) >= CURDATE()
                ORDER BY a.id";
        return AllFetch($sql, ['user_id' => $userId]);
    }
    public function getByName($name)
    {
        $sql = "SELECT * FROM medicines WHERE name = :name";
        return OneFetch($sql, ['name' => $name]);
    }
}
