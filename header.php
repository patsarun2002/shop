<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include the Font Awesome stylesheet -->
</head>

<?php
// นับจำนวนสินค้าทั้งหมดในตะกร้า
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cart_count += $quantity;  // นับจำนวนสินค้าที่ผู้ใช้เพิ่มในตะกร้าทั้งหมด
    }
}
?>

<header>
    <div class="logo">
        <h1><a href="home.php" style="text-decoration: none; color: inherit;">Jewelry Store</a></h1>
    </div>
    <nav>
    <ul>
        <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
        <li class="dropdown">
            <a href="index.php"><i class="fas fa-box"></i> Product</a>
            <div class="dropdown-content">
                <?php
                include("connectdb.php");
                $sql = "SELECT c_id, c_name FROM category";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<a href="products.php?category_id=' . $row['c_id'] . '">' . $row['c_name'] . '</a>';
                    }
                } else {
                    echo '<a href="#">No categories available</a>';
                }
                mysqli_close($conn);
                ?>
            </div>
        </li>
        <li><a href="about.php"><i class="fas fa-info-circle"></i> About us</a></li>
        <li><a href="contact.php"><i class="fas fa-envelope"></i> Contacts</a></li>
        <?php if (isset($_SESSION['user_id'])) { ?>
            <li class="dropdown">
                <a href="#"><i class="fas fa-user"></i> Profile</a>
                <div class="dropdown-content">
                    <a href="profile.php">View Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
            </li>
            <li><a href="cart.php">
                <i class="fas fa-shopping-cart"></i> Cart 
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