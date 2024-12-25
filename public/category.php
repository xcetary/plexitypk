<?php
require 'db.php';

$conn->set_charset("utf8"); // Устанавливаем кодировку

// Получаем название категории из URL, например: category.php?category=Категория%201
$category = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT Товары.ID, Товары.Название, Товары.Описание, Товары.Цена, Товары.Фото
        FROM Товары
        LEFT JOIN Catalog ON Товары.ID = Catalog.IDтовара
        WHERE Catalog.Категория = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $category);
$stmt->execute();
$result = $stmt->get_result();

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

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Товары - <?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="css/stylesproc.css">
</head>
<body>

<h1>Товары категории: <?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></h1>

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
        <p>Товары не найдены в этой категории.</p>
    <?php endif; ?>
</div>

</body>
</html>
