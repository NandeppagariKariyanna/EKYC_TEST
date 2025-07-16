<?php
session_start();

// Sample product catalog
$products = [
    1 => ['name' => 'Laptop', 'price' => 45000],
    2 => ['name' => 'Smartphone', 'price' => 22000],
    3 => ['name' => 'Headphones', 'price' => 1500],
    4 => ['name' => 'Book', 'price' => 500],
];

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    if (isset($products[$product_id])) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
        $message = "{$products[$product_id]['name']} added to cart!";
    }
}

// Remove from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = intval($_POST['product_id']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        $message = "{$products[$product_id]['name']} removed from cart!";
    }
}

// Checkout
if (isset($_POST['checkout'])) {
    $_SESSION['cart'] = [];
    $message = "Thank you for your purchase!";
}

// Helper to render cart
function renderCart($products, $cart) {
    $total = 0;
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }
    echo "<table border='1'><tr><th>Product</th><th>Qty</th><th>Price</th><th>Action</th></tr>";
    foreach ($cart as $id => $qty) {
        $name = htmlspecialchars($products[$id]['name']);
        $price = $products[$id]['price'] * $qty;
        $total += $price;
        echo "<tr>
                <td>$name</td>
                <td>$qty</td>
                <td>₹$price</td>
                <td>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='product_id' value='$id'>
                        <input type='submit' name='remove_from_cart' value='Remove'>
                    </form>
                </td>
              </tr>";
    }
    echo "<tr><td colspan='2'><strong>Total</strong></td><td colspan='2'><strong>₹$total</strong></td></tr>";
    echo "</table>";
    echo "<form method='post'><input type='submit' name='checkout' value='Checkout'></form>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple PHP E-Commerce App</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .product { margin-bottom: 20px; }
        .cart { margin-top: 30px; }
        .msg { color: green; }
    </style>
</head>
<body>
    <h1>Welcome to Our E-Commerce Store</h1>
    <?php if (!empty($message)): ?>
        <p class="msg"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <h2>Products</h2>
    <?php foreach ($products as $id => $prod): ?>
        <div class="product">
            <strong><?= htmlspecialchars($prod['name']) ?></strong><br>
            Price: ₹<?= $prod['price'] ?><br>
            <form method="post">
                <input type="hidden" name="product_id" value="<?= $id ?>">
                <input type="submit" name="add_to_cart" value="Add to Cart">
            </form>
        </div>
    <?php endforeach; ?>

    <div class="cart">
        <h2>Your Cart</h2>
        <?php renderCart($products, $_SESSION['cart']); ?>
    </div>
</body>
</html>