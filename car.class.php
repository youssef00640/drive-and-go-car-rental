<?php
require_once(__DIR__ . '/config.php');

class car
{
    public $car_id;
    public $name;
    public $brand;
    public $price;
    public $description;
    public $image;

    private function db()
    {
        return (new connection())->CNbase();
    }

    // Only allow a bare file name for images (no paths, no quotes).
    private function safeImage()
    {
        $img = basename((string)$this->image);
        return preg_match('/^[\w.-]+\.(jpe?g|png|webp|gif|svg)$/i', $img) ? $img : '';
    }

    function addCar()
    {
        $st = $this->db()->prepare(
            "INSERT INTO cars (name, brand, price, description, image) VALUES (?, ?, ?, ?, ?)");
        $st->execute([$this->name, $this->brand, (float)$this->price,
                      $this->description, $this->safeImage()]);
    }

    function listCars()
    {
        return $this->db()->query("SELECT * FROM cars");
    }

    function listFeatured()
    {
        return $this->db()->query("SELECT * FROM cars LIMIT 3");
    }

    function getCarById($id)
    {
        $st = $this->db()->prepare("SELECT * FROM cars WHERE car_id = ?");
        $st->execute([(int)$id]);
        return $st;
    }

    function updateCar($id)
    {
        $st = $this->db()->prepare(
            "UPDATE cars SET name = ?, brand = ?, price = ?, description = ?, image = ?
             WHERE car_id = ?");
        $st->execute([$this->name, $this->brand, (float)$this->price,
                      $this->description, $this->safeImage(), (int)$id]);
    }

    function deleteCar($id)
    {
        $st = $this->db()->prepare("DELETE FROM cars WHERE car_id = ?");
        $st->execute([(int)$id]);
    }
}
?>
