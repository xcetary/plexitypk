<?php
require 'db.php'; // подключение к базе данных

// ID категории для процессоров
$category_id = 6; // ID категории "Процессоры" в базе данных

// Запрос на выборку товаров для данной категории
$sql = "SELECT * FROM goods WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

// Проверка наличия товаров
if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC); // Получаем все товары в виде массива
} else {
    $products = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Охлаждение компьютера</title>
    <link rel="stylesheet" href="css/stylesproc.css">
</head>
<body>

<h1>Охлаждение компьютера</h1>

<div class="products-container">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?= htmlspecialchars($product['Фото'], ENT_QUOTES, 'UTF-8') ?>" alt="Фото товара">
                </div>
                <div class="product-info">
                    <h3 class="product-title"><?= htmlspecialchars($product['Название'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p class="product-description"><?= htmlspecialchars($product['Описание'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="product-price">Цена: <?= htmlspecialchars($product['Цена'], ENT_QUOTES, 'UTF-8') ?> ₽</p>
                    <button class="buy-button">Купить</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Товары не найдены.</p>
    <?php endif; ?>
</div>
<script>
    fetch('navbar.html')
        .then(response => response.text())
        .then(html => {
            document.body.insertAdjacentHTML('afterbegin', html);
        });
</script>
</body>
</html>