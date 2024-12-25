<?php
require 'db.php';

$conn->set_charset("utf8"); // Устанавливаем кодировку

// Запрос для получения товаров с категориями
$sql = "SELECT Товары.ID, Товары.Название, Товары.Описание, Товары.Цена, Товары.Фото, Catalog.Категория
        FROM Товары
        LEFT JOIN Catalog ON Товары.ID = Catalog.IDтовара";

$result = $conn->query($sql);

if (!$result) {
    die("Ошибка SQL: " . $conn->error . "<br>");
}

if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
}

$conn->close();

?>
