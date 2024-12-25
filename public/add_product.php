<?php
require 'db.php'; // Подключение к базе данных

// Проверяем, что запрос пришел методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $name = $_POST['Название'] ?? '';
    $description = $_POST['Описание'] ?? '';
    $price = $_POST['Цена'] ?? 0;
    $photo = $_POST['Фото'] ?? '';
    $category_id = $_POST['Категория'] ?? '';

    // Проверяем обязательные поля
    if (empty($name) || empty($description) || empty($price) || empty($photo) || empty($category_id)) {
        die("Все поля должны быть заполнены!");
    }

    // Подготавливаем запрос к базе данных
    $sql = "INSERT INTO goods (Название, Описание, Цена, Фото, category_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }

    $stmt->bind_param("ssdsi", $name, $description, $price, $photo, $category_id);

    try {
        // Выполняем запрос
        $stmt->execute();

        // Перенаправляем на страницу с сообщением об успешном добавлении
        echo "<script>
                alert('Товар успешно добавлен!');
                window.location.href = 'admin.html';
              </script>";
    } catch (mysqli_sql_exception $e) {
        die("Ошибка при добавлении товара: " . $e->getMessage());
    } finally {
        $stmt->close();
        $conn->close();
    }
} else {
    // Если запрос не POST, возвращаем ошибку
    die("Неправильный метод запроса.");
}
?>
