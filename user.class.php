<?php
require_once(__DIR__ . '/config.php');

class user
{
    public $user_id;
    public $user_name;
    public $email;
    public $password;
    public $phone;
    public $role;

    private function db()
    {
        return (new connection())->CNbase();
    }

    function addUser()
    {
        $st = $this->db()->prepare(
            "INSERT INTO users (user_name, email, password, phone, role)
             VALUES (?, ?, ?, ?, 'user')");
        $st->execute([$this->user_name, $this->email,
                      password_hash($this->password, PASSWORD_DEFAULT), $this->phone]);
    }

    function findEmail()
    {
        $st = $this->db()->prepare("SELECT count(*) FROM users WHERE email = ?");
        $st->execute([$this->email]);
        return $st;
    }

    // Returns the user row on success, false otherwise.
    function login()
    {
        $st = $this->db()->prepare("SELECT * FROM users WHERE email = ?");
        $st->execute([$this->email]);
        $row = $st->fetch();
        if ($row && password_verify($this->password, $row['password'])) {
            return $row;
        }
        return false;
    }

    function getUserById($id)
    {
        $st = $this->db()->prepare("SELECT * FROM users WHERE user_id = ?");
        $st->execute([(int)$id]);
        return $st;
    }

    function updateUser($id)
    {
        if ($this->password !== null && $this->password !== '') {
            $st = $this->db()->prepare(
                "UPDATE users SET user_name = ?, phone = ?, password = ? WHERE user_id = ?");
            $st->execute([$this->user_name, $this->phone,
                          password_hash($this->password, PASSWORD_DEFAULT), (int)$id]);
        } else {
            $st = $this->db()->prepare(
                "UPDATE users SET user_name = ?, phone = ? WHERE user_id = ?");
            $st->execute([$this->user_name, $this->phone, (int)$id]);
        }
    }
}
?>
