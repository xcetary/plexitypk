<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Поиск пользователя и его роли
    $stmt = $conn->prepare("
        SELECT aut.ID, aut.Пароль, user.Роль 
        FROM aut 
        JOIN user ON aut.ID = user.ID 
        WHERE Логин = ?
    ");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Проверка пароля
        if (password_verify($password, $row['Пароль'])) {
            session_start();
            $_SESSION['user'] = $login;
            $_SESSION['role'] = $row['Роль'];

            // Перенаправление в зависимости от роли
            if ($row['Роль'] === 'Админ') {
                header("Location: admin.html");
                exit;
            } elseif ($row['Роль'] === 'Покупатель') {
                header("Location: main.html");
                exit;
            }
        } else {
            echo "Неправильный пароль.";
        }
    } else {
        echo "Пользователь не найден.";
    }

    $stmt->close();
}
$conn->close();
?>
