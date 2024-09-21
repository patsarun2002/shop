<?php
session_start();
include("connectdb.php");

// รับคำค้นหา
$kw = isset($_POST['search']) ? $_POST['search'] : '';
// รับ category_id จาก URL
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';

// สร้างคำสั่ง SQL สำหรับค้นหาสินค้า
$sql = "SELECT * FROM product WHERE (p_name LIKE '%{$kw}%' OR p_detail LIKE '%{$kw}%')";

// เพิ่มเงื่อนไขสำหรับ category_id ถ้ามีการกำหนด
if (!empty($category_id)) {
    $sql .= " AND c_id = '{$category_id}'";
}

$rs = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="styles.css">
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
    // ตรวจสอบว่ามีข้อมูลสินค้าหรือไม่
    if (mysqli_num_rows($rs) > 0) {
        while ($data = mysqli_fetch_array($rs)) {
    ?>
        <div class="product-card">
            <img src="images/<?= $data['p_id']; ?>.<?= htmlspecialchars($data['p_img']); ?>" width="150" height="150" alt="<?= htmlspecialchars($data['p_name']); ?>">
            <h3><?= htmlspecialchars($data['p_name']); ?></h3>
            <p><?= htmlspecialchars($data['p_detail']); ?></p>
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

<?php
// Close the database connection once at the end of the script
mysqli_close($conn);
?>
</body>
</html>
