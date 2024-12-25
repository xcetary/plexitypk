<?php
require 'db.php'; // Подключение к базе данных

$user_id = 1;

// Получение товаров из корзины
$sql = "SELECT cart.id AS cart_id, goods.Название, goods.Цена, cart.quantity 
        FROM cart
        JOIN goods ON cart.product_id = goods.ID
        WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>
    <style>body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
}

header {
    background-color: #007bff;
    color: white;
    padding: 1rem;
    text-align: center;
}

main {
    padding: 2rem;
}

.cart-container {
    margin: auto;
    width: 90%;
    max-width: 1000px;
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1.5rem;
}

.cart-table th, .cart-table td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    text-align: center;
}

.cart-table th {
    background-color: #007bff;
    color: white;
}

.cart-summary {
    text-align: right;
    margin-top: 1rem;
}

.cart-summary h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.update-quantity-form input[type="number"] {
    width: 60px;
    padding: 0.5rem;
    text-align: center;
    margin-right: 0.5rem;
}

.remove-button, .checkout-button {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    cursor: pointer;
    border-radius: 4px;
    font-size: 1rem;
}

.checkout-button {
    background-color: #28a745;
}

.remove-button:hover, .checkout-button:hover {
    opacity: 0.9;
}
</style>
   

    <main>
        <?php if (!empty($cart_items)): ?>
            <div class="cart-container">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Название товара</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>Итоговая цена</th>
                            <th>Удалить</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['Название'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($item['Цена'], ENT_QUOTES, 'UTF-8') ?> ₽</td>
                                <td>
                                    <form action="update_cart.php" method="post" class="update-quantity-form">
                                        <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                                        <button type="submit">Обновить</button>
                                    </form>
                                </td>
                                <td><?= htmlspecialchars($item['Цена'] * $item['quantity'], ENT_QUOTES, 'UTF-8') ?> ₽</td>
                                <td>
                                    <form action="remove_from_cart.php" method="post">
                                        <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                        <button type="submit" class="remove-button">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="cart-summary">
                <h3>Итоговая сумма: 
                    <?= array_reduce($cart_items, function ($sum, $item) {
                        return $sum + ($item['Цена'] * $item['quantity']);
                    }, 0) ?> ₽
                </h3>
                <form action="checkout.php" method="post">
                    <button type="submit" class="checkout-button">Оформить заказ</button>
                </form>
            </div>
        <?php else: ?>
            <p>Ваша корзина пуста.</p>
        <?php endif; ?>
    </main>
    <script>
        fetch('navbar.html')
            .then(response => response.text())
            .then(html => {
                document.body.insertAdjacentHTML('afterbegin', html);
            });
    </script>
</body>
</html>
