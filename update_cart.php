<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity > 0) {
        $_SESSION['cart'][$product_id] = $quantity; // อัปเดตจำนวนสินค้า
    } else {
        unset($_SESSION['cart'][$product_id]); // ถ้าจำนวนเป็น 0 ให้เอาสินค้าออกจากตะกร้า
    }
}

// ส่งกลับไปยังหน้าตะกร้าสินค้า
header("Location: cart.php");
exit();
?>
