<?php
// เริ่ม session
session_start();

// นับจำนวนสินค้าทั้งหมดในตะกร้า
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cart_count += $quantity;
    }
}

// เชื่อมต่อฐานข้อมูล
include("connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // ตรวจสอบว่ารหัสผ่านตรงกันหรือไม่
    if ($password === $confirm_password) {
        $hashed_password = md5($password);

        // ตรวจสอบว่า email นี้มีอยู่ในระบบหรือยัง
        $check_query = "SELECT * FROM users WHERE email='$email'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            // เพิ่มข้อมูลผู้ใช้ใหม่
            $sql = "INSERT INTO users (username, password, email, phone, address) 
                    VALUES ('$username', '$hashed_password', '$email', '$phone', '$address')";

            if (mysqli_query($conn, $sql)) {
                // ถ้าสำเร็จ redirect ไปยังหน้า login
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "<p>Email นี้ถูกใช้งานแล้ว</p>";
        }
    } else {
        echo "<p>รหัสผ่านไม่ตรงกัน</p>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

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

<div class="form-container signup-form">
    <h2>Sign Up</h2>
    <form method="POST" action="signup.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" required>

        <label for="address">Address:</label>
        <textarea name="address" id="address" required></textarea>

        <button type="submit">Sign Up</button>
    </form>
</div>

<footer>
    <p>Follow us: 
        <a href="#">Facebook</a> | 
        <a href="#">Instagram</a> | 
        <a href="#">Twitter</a>
    </p>
    <p>© 2024 Jewelry Store. All rights reserved.</p>
</footer>

</body>
</html>
