<?php
include 'config.php';
?>
<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'user_system');

// Check if the user is logged in as Admin
if (!isset($_SESSION['role'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
    exit();
}

// Handle user approval
if (isset($_POST['approve_user'])) {
    $user_id = $_POST['user_id']; // Get the user ID from the form
    $approved_by = 'Administrator'; // Set the approver as Admin

    // Prepare and execute the update statement
    $stmt = $conn->prepare("UPDATE users SET status = 'approved', approved_by = ? WHERE id = ?");
    $stmt->bind_param("si", $approved_by, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('User approved successfully.');</script>";
    } else {
        echo "<script>alert('Failed to approve user.');</script>";
    }
}

// Handle user decline
if (isset($_POST['decline_user'])) {
    $user_id = $_POST['user_id']; // Get the user ID from the form

    // Prepare and execute the update statement to decline the user
    $stmt = $conn->prepare("UPDATE users SET status = 'declined', approved_by = NULL WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('User declined successfully.');</script>";
    } else {
        echo "<script>alert('Failed to decline user.');</script>";
    }
}

// Handle adding a new HOD
if (isset($_POST['add_hod'])) {
    $hod_id = $_POST['hod_id']; // Get the HOD ID from the form
    $hod_password = password_hash($_POST['hod_password'], PASSWORD_DEFAULT); // Hash the password

    // Prepare and execute the insert statement for the new HOD
    $stmt = $conn->prepare("INSERT INTO admins_users (id, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $hod_id, $hod_password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('HOD added successfully.');</script>";
    } else {
        echo "<script>alert('Failed to add HOD.');</script>";
    }
}

// Fetch users awaiting approval
$result = $conn->query("SELECT * FROM users WHERE status = 'pending'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg text-center">
        <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
        
        <h2 class="text-xl mb-4">Users Awaiting Approval</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Full Name</th>
                        <th class="border px-4 py-2">Email</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="border px-4 py-2">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="approve_user" class="bg-green-500 text-white rounded p-2 hover:bg-green-600 transition">Approve</button>
                                    <button type="submit" name="decline_user" class="bg-red-500 text-white rounded p-2 hover:bg-red-600 transition">Decline</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users awaiting approval.</p>
        <?php endif; ?>
        
        <h2 class="text-xl mb-4 mt-6">Add New Head of Department (HOD)</h2>
        <form method="POST">
            <div class="mb-4">
                <input type="text" name="hod_id" placeholder="HOD ID" required class="border rounded w-full p-2">
            </div>
            <div class="mb-4">
                <input type="password" name="hod_password" placeholder="HOD Password" required class="border rounded w-full p-2">
            </div>
            <button type="submit" name="add_hod" class="bg-blue-500 text-white rounded w-full p-2 hover:bg-blue-600 transition">Add HOD</button>
        </form>
        
        <form method="POST" action="admin_logout.php" class="mt-4">
            <button type="submit" class="bg-red-500 text-white rounded w-full p-2 hover:bg-red-600 transition">Logout</button>
        </form>
    </div>
</body>
</html>
