<?php
// เริ่มต้น session เพื่อให้แน่ใจว่าผู้ใช้ได้ล็อกอินแล้ว
session_start();
include("connectdb.php");

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูลเพื่อนำมาแสดงในฟอร์ม
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "User not found.";
        exit;
    }

    // ตรวจสอบว่ามีการส่งฟอร์มแล้วหรือยัง
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // รับค่าจากฟอร์ม
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        // ตรวจสอบว่าชื่อผู้ใช้หรืออีเมลมีอยู่แล้วในฐานข้อมูลหรือไม่ (ยกเว้นข้อมูลของผู้ใช้ที่กำลังแก้ไข)
        $checkUserQuery = "SELECT * FROM users WHERE (username = '$username' OR email = '$email') AND id != '$user_id'";
        $checkUserResult = mysqli_query($conn, $checkUserQuery);

        if (mysqli_num_rows($checkUserResult) > 0) {
            // ถ้าชื่อผู้ใช้หรืออีเมลมีอยู่แล้ว
            echo "Username or Email already exists.";
        } else {
            // อัปเดตข้อมูลผู้ใช้ในฐานข้อมูล
            $sql = "UPDATE users SET username = '$username', email = '$email', phone = '$phone', address = '$address' WHERE id = '$user_id'";

            if (mysqli_query($conn, $sql)) {
                // แสดงข้อความเมื่ออัปเดตข้อมูลสำเร็จ
                echo "Profile updated successfully!";
                // เปลี่ยนเส้นทางกลับไปที่หน้าโปรไฟล์
                header("Location: profile.php");
                exit;
            } else {
                // ข้อผิดพลาดในการอัปเดตข้อมูล
                echo "Error updating profile: " . mysqli_error($conn);
            }
        }
    }
} else {
    // ถ้าผู้ใช้ยังไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้า login
    header("Location: login.php");
    exit;
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
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

<form method="POST" action="" class="edit-profile-form">
    <h1>Edit Profile</h1>
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?= $user['username']; ?>" required>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= $user['email']; ?>" required>
    </div>

    <div class="form-group">
        <label for="phone">Phone:</label>
        <input type="text" name="phone" value="<?= $user['phone']; ?>" required>
    </div>

    <div class="form-group">
        <label for="address">Address:</label>
        <textarea name="address" rows="2" required><?= $user['address']; ?></textarea>
    </div>

    <button type="submit" class="update-btn">Update</button>
</form>


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
