<?php
$host = "MySQL-8.2";
$username = "root";
$password = ""; // Укажите ваш пароль
$dbname = "plexitypk";

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Ошибка подключения: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4"); // Устанавливаем кодировку
} catch (Exception $e) {
    echo "Не удалось подключиться: " . $e->getMessage();
    exit;
}
?>
