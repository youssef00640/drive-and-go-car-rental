<?php
// Database settings come from environment variables so no credentials live in the repo.
// Defaults match a local XAMPP install.
class connection
{
    public function CNbase()
    {
        static $pdo = null;
        if ($pdo === null) {
            $dsn  = getenv('DB_DSN')  ?: 'mysql:host=localhost;dbname=car_rental;charset=utf8mb4';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return $pdo;
    }
}

// Escape output to prevent XSS.
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Simple per-session CSRF token.
function csrf_token()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check()
{
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
        http_response_code(400);
        exit('Invalid request.');
    }
}
?>
