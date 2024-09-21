<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    
<?php
// นับจำนวนสินค้าทั้งหมดในตะกร้า
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cart_count += $quantity;  // นับจำนวนสินค้าที่ผู้ใช้เพิ่มในตะกร้าทั้งหมด
    }
}
?>

</head>
<body>
    
<?php include('header.php'); ?>

<div class="search-bar">
    <form method="POST" action="">
        <input type="text" name="search" placeholder="Search for products..." value="<?= isset($_POST['search']) ? $_POST['search'] : '' ?>">
        <button type="submit"><i class="fas fa-search"></i> Search</button>
    </form>
</div>


<main>
    <section class="products">
    <?php
    include("connectdb.php");

    $kw = isset($_POST['search']) ? $_POST['search'] : '';
    $limit = 16; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT * FROM product WHERE (p_name LIKE '%{$kw}%' OR p_detail LIKE '%{$kw}%') LIMIT $limit OFFSET $offset";
    $rs = mysqli_query($conn, $sql);

    if (mysqli_num_rows($rs) > 0) {
        while ($data = mysqli_fetch_array($rs)) {
    ?>
        <div class="product-card">
            <img src="images/<?= $data['p_id']; ?>.<?= $data['p_img']; ?>" width="150" height="150" alt="<?= $data['p_name']; ?>">
            <h3><?= $data['p_name']; ?></h3>
            <p><?= $data['p_detail']; ?></p>
            <p><strong><?= number_format($data['p_price'], 2); ?> THB</strong></p>
            
            <!-- ปุ่มหยิบสินค้า -->
            <form method="POST" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?= $data['p_id']; ?>">
                <button type="submit" class="add-to-cart-btn"><i class="fas fa-cart-plus"></i> Add to Cart</button>
            </form>
        </div>
    <?php
        }
    } else {
        echo "<p>No products found matching your search.</p>";
    }

    mysqli_close($conn);
    ?>
    </section>
</main>

<div class="pagination">
        <button>«</button>
        <button class="active">1</button>
        <button>2</button>
        <button>3</button>
        <button>»</button>
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