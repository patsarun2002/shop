<?php
// เริ่ม session เพื่อใช้จัดการ login/logout
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

<?php include('header.php'); ?>

    <main>
        <section class="products">
        <?php
        // เชื่อมต่อฐานข้อมูล
        include("connectdb.php");

        // รับคำค้นหา
        $kw = isset($_POST['search']) ? $_POST['search'] : '';

        // สร้างคำสั่ง SQL สำหรับค้นหาสินค้า
        $sql = "SELECT * FROM product WHERE (p_name LIKE '%{$kw}%' OR p_detail LIKE '%{$kw}%')";
        $rs = mysqli_query($conn, $sql);

        // ตรวจสอบว่ามีข้อมูลสินค้าหรือไม่
        if (mysqli_num_rows($rs) > 0) {
            // วนลูปแสดงผลสินค้า
            while ($data = mysqli_fetch_array($rs)) {
        ?>
            <div class="product-card">
                <img src="images/<?= $data['p_id']; ?>.<?= $data['p_img']; ?>" width="150" height="150" alt="<?= $data['p_name']; ?>">
                <h3><?= $data['p_name']; ?></h3>
                <p><?= $data['p_detail']; ?></p>
            </div>
        <?php
            }
        } else {
            // แสดงข้อความหากไม่มีสินค้าที่ค้นหาเจอ
            echo "<p>No products found matching your search.</p>";
        }

        // ปิดการเชื่อมต่อฐานข้อมูล
        mysqli_close($conn);
        ?>
        </section>
    </main>

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
