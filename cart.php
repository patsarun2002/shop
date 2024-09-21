<?php
session_start();
include("connectdb.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jewelry Store - Shopping Cart</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php
// Count the total items in the cart
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>

<header>
    <div class="logo">
        <h1><a href="home.php" style="text-decoration: none; color: inherit;">Jewelry Store</a></h1>
    </div>
    <nav>
        <ul>
            <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
            <li class="dropdown">
                <a href="index.php"><i class="fas fa-box"></i> Products</a>
                <div class="dropdown-content">
                    <?php
                    $sql = "SELECT c_id, c_name FROM category";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<a href="products.php?category_id=' . $row['c_id'] . '">' . $row['c_name'] . '</a>';
                        }
                    } else {
                        echo '<a href="#">No categories available</a>';
                    }
                    ?>
                </div>
            </li>
            <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
            <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-user"></i> Profile</a>
                    <div class="dropdown-content">
                        <a href="profile.php">View Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </li>
                <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart 
                    <?php if ($cart_count > 0) { ?>
                        <span class="cart-count"><?= $cart_count; ?></span>
                    <?php } ?>
                </a></li>
            <?php } else { ?>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="signup.php"><i class="fas fa-user-plus"></i> Sign Up</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>

<main>
    <div class="container">
        <h2>Your Cart</h2>

        <?php
        if (empty($_SESSION['cart'])) {
            echo "<p>Your cart is currently empty.</p>";
        } else {
            $total = 0;
        ?>

<table class="cart-table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Image</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($_SESSION['cart'] as $product_id => $quantity) { 
            $sql = "SELECT * FROM product WHERE p_id = $product_id";
            $result = mysqli_query($conn, $sql);
            $product = mysqli_fetch_assoc($result);
            $subtotal = $product['p_price'] * $quantity;
            $total += $subtotal;
        ?>
        <tr>
            <td><?= htmlspecialchars($product['p_name']); ?></td>
            <td><img src="images/<?= htmlspecialchars($product_id); ?>.jpg" alt="<?= htmlspecialchars($product['p_name']); ?>" style="width: 100px; height: auto;"></td>
            <td><?= number_format($product['p_price'], 2); ?> THB</td>
            <td>
                <form action="update_cart.php" method="POST" class="cart-update-form">
                    <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                    <input type="number" name="quantity" value="<?= $quantity; ?>" min="1">
                    <button type="submit" class="update-btn">Update</button>
                </form>
            </td>
            <td><?= number_format($subtotal, 2); ?> THB</td>
            <td>
                <form action="remove_from_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                    <button type="submit" class="remove-btn">Remove</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<div class="cart-summary">
    <h3>Total: <?= number_format($total, 2); ?> THB</h3>

    <form action="checkout.php" method="POST">
        <button type="submit" class="checkout-btn">Proceed to Checkout</button>
    </form>
</div>


        <?php } ?>
    </div>
</main>

<footer>
    <div class="container">
        <p>Follow us: 
            <a href="#">Facebook</a> | 
            <a href="#">Instagram</a> | 
            <a href="#">Twitter</a>
        </p>
        <p>&copy; 2024 Jewelry Store. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
