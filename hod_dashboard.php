<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'user_system');

// Check if the user is logged in as HOD
if (!isset($_SESSION['role'])) {
    header('Location: admin_login.php'); // Redirect to login if not logged in
    exit();
}

// Handle user approval
if (isset($_POST['approve_user'])) {
    $user_id = $_POST['user_id']; // Get the user ID from the form
    $approved_by = 'Head of The Department'; // Set the approver as HOD

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

// Fetch users awaiting approval
$result = $conn->query("SELECT * FROM users WHERE status = 'pending'");

// Count total registered users
$total_users_result = $conn->query("SELECT COUNT(*) as total_users FROM users");
$total_users_row = $total_users_result->fetch_assoc();
$total_users = $total_users_row['total_users'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>HOD Dashboard</title>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg text-center">
        <h1 class="text-2xl font-bold mb-4">HOD Dashboard</h1>

        <h2 class="text-lg mb-4">Total Registered Users: <span class="font-bold"><?php echo htmlspecialchars($total_users); ?></span></h2>
        
        <h2 class="text-xl mb-4">Users Awaiting Approval</h2>
        <hr>
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
        
        
        <form method="POST" action="admin_logout.php" class="mt-4">
            <button type="submit" class="bg-red-500 text-white rounded w-full p-2 hover:bg-red-600 transition">Logout</button>
        </form>
    </div>
</body>
</html>
