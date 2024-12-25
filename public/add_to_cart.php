<?php
require 'db.php'; // Подключение к базе данных

// Получаем данные из запроса
$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Не указан ID товара.']);
    exit;
}

// Предполагаем, что пользователь авторизован, и у нас есть его ID
$user_id = 1; // В реальной системе ID берётся из сессии

// Проверяем, есть ли уже этот товар в корзине
$sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Если товар уже в корзине, увеличиваем количество
    $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
} else {
    // Если товара нет в корзине, добавляем его
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
}

// Выполняем запрос
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка добавления в корзину.']);
}

$conn->close();
?>
