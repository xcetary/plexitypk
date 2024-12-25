<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Проверка, существует ли пользователь
    $stmt = $conn->prepare("SELECT * FROM aut WHERE Логин = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Пользователь с таким логином уже существует.');</script>";
    } else {
        // Хешируем пароль перед сохранением
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Добавляем запись в таблицу aut
        $stmt = $conn->prepare("INSERT INTO aut (Логин, Пароль) VALUES (?, ?)");
        $stmt->bind_param("ss", $login, $hashedPassword);
        
        if ($stmt->execute()) {
            $lastInsertedId = $conn->insert_id; // Получаем ID новой записи

            // Добавляем запись в таблицу user с ролью "Покупатель"
            $stmt = $conn->prepare("INSERT INTO user (ID, Роль) VALUES (?, 'Покупатель')");
            $stmt->bind_param("i", $lastInsertedId);

            if ($stmt->execute()) {
                echo "Регистрация успешна! Вы зарегистрированы как Покупатель.";
            } else {
                echo "Ошибка при добавлении роли.";
            }
        } else {
            echo "Ошибка при регистрации.";
        }
    }
    $stmt->close();
}
$conn->close();
?>