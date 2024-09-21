<?php
session_start();
include("connectdb.php");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "User not found.";
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
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

<div class="form-container profile-form">
    <h1>Profile</h1>
    <form>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" value="<?= htmlspecialchars($user['username']); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" value="<?= htmlspecialchars($user['email']); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" id="phone" value="<?= htmlspecialchars($user['phone']); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" rows="2" readonly><?= htmlspecialchars($user['address']); ?></textarea>
        </div>

        <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
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
