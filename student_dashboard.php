<?php
include 'config.php';
?>
<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'user_system');

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch user data
$user_id = $_SESSION['student_id'];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

// Handle profile update
if (isset($_POST['update_profile'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    
    // Handle photo upload
    $photo = $_FILES['photo']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
    
    // Update user profile in the database
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, photo = ? WHERE id = ?");
    $stmt->bind_param("sssi", $full_name, $email, $photo, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Profile updated successfully.');</script>";
    } else {
        echo "<script>alert('Failed to update profile.');</script>";
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
    // Update user password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Password changed successfully.');</script>";
    } else {
        echo "<script>alert('Failed to change password.');</script>";
    }
}

// Activity log
$activity_result = $conn->query("SELECT action, timestamp FROM user_activity WHERE user_id = $user_id ORDER BY timestamp DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>User Dashboard</title>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h1 class="text-2xl font-bold mb-4 text-center">User Dashboard</h1>

        <!-- User Profile Information -->
        <div class="flex items-center mb-4">
            <img src="uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="User Photo" class="rounded-full w-24 h-24 mr-4">
            <div>
                <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($user['full_name']); ?></h2>
                <p class="text-gray-600"><?php echo htmlspecialchars($user['email']); ?></p>
                <p class="text-gray-600">Status: <?php echo htmlspecialchars($user['status']); ?></p>
                <p class="text-gray-600">Approved By: <?php echo htmlspecialchars($user['approved_by'] ?? 'N/A'); ?></p>
            </div>
        </div>

        <!-- Profile Update Form -->
        <h2 class="text-lg font-semibold mb-2">Update Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" placeholder="Full Name" required class="border p-2 w-full mb-2">
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email" required class="border p-2 w-full mb-2">
            <input type="file" name="photo" class="border p-2 w-full mb-4">
            <button type="submit" name="update_profile" class="bg-blue-500 text-white rounded w-full p-2 hover:bg-blue-600 transition">Update Profile</button>
        </form>

        <!-- Change Password Form -->
        <h2 class="text-lg font-semibold mb-2">Change Password</h2>
        <form method="POST">
            <input type="password" name="new_password" placeholder="New Password" required class="border p-2 w-full mb-4">
            <button type="submit" name="change_password" class="bg-blue-500 text-white rounded w-full p-2 hover:bg-blue-600 transition">Change Password</button>
        </form>

        <!-- Activity Log -->
        <h2 class="text-lg font-semibold mb-2">Activity Log</h2>
        <?php if ($activity_result->num_rows > 0): ?>
            <ul class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <?php while ($activity = $activity_result->fetch_assoc()): ?>
                    <li class="border-b py-2"><?php echo htmlspecialchars($activity['action']) . " - " . htmlspecialchars($activity['timestamp']); ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No activity recorded.</p>
        <?php endif; ?>

        <form method="POST" action="logout.php" class="mt-4">
            <button type="submit" class="bg-red-500 text-white rounded w-full p-2 hover:bg-red-600 transition">Logout</button>
        </form>
        
    </div>
</body>
</html>
