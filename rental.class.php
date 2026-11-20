<?php
require_once(__DIR__ . '/config.php');

class rental
{
    public $rental_id;
    public $user_id;
    public $car_id;
    public $start_date;
    public $end_date;
    public $total_price;

    private function db()
    {
        return (new connection())->CNbase();
    }

    function addRental()
    {
        $st = $this->db()->prepare(
            "INSERT INTO rentals (user_id, car_id, start_date, end_date, total_price)
             VALUES (?, ?, ?, ?, ?)");
        $st->execute([(int)$this->user_id, (int)$this->car_id,
                      $this->start_date, $this->end_date, $this->total_price]);
    }

    function listRentals()
    {
        return $this->db()->query(
            "SELECT r.rental_id, u.user_name, c.name, c.brand,
                    r.start_date, r.end_date, r.total_price
             FROM rentals r
             JOIN users u ON r.user_id = u.user_id
             JOIN cars  c ON r.car_id  = c.car_id
             ORDER BY r.rental_id DESC");
    }

    function getRentalsByCar($car_id)
    {
        $st = $this->db()->prepare(
            "SELECT start_date, end_date FROM rentals
             WHERE car_id = ? AND end_date >= ? ORDER BY start_date");
        $st->execute([(int)$car_id, date('Y-m-d')]);
        return $st;
    }

    function countConflict($car_id, $start, $end)
    {
        $st = $this->db()->prepare(
            "SELECT count(*) FROM rentals
             WHERE car_id = ? AND start_date <= ? AND end_date >= ?");
        $st->execute([(int)$car_id, $end, $start]);
        return $st->fetchColumn(0);
    }
}
?>
