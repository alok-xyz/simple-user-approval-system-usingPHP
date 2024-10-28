<?php
include 'config.php';
?>
<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'user_system');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Get the selected role

    // Prepare the query based on the selected role
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            if ($user['role'] == 'hod') {
                header('Location: hod_dashboard.php');
            } else {
                header('Location: admin_dashboard.php');
            }
            exit();
        } else {
            echo "<script>alert('Invalid username or password');</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Admin Login</title>
    <script>
        function fillUsername() {
            var role = document.getElementById("role").value;
            var usernameInput = document.getElementById("username");

            // Set the username based on the selected role
            if (role === "hod") {
                usernameInput.value = "h1"; // HOD username
            } else if (role === "admin") {
                usernameInput.value = "a1"; // Admin username
            } else {
                usernameInput.value = ""; // Clear username if no role selected
            }
        }
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h1 class="text-lg font-bold mb-6">Admin Login</h1>
        <form method="POST">
            <select id="role" name="role" required class="border rounded w-full p-2 mb-4" onchange="fillUsername()">
                <option value="">Select Role</option>
                <option value="hod">Head of The Department</option>
                <option value="admin">Administrator</option>
            </select>
            <input type="text" id="username" name="username" placeholder="Username" required class="border rounded w-full p-2 mb-4" readonly>
            <input type="password" name="password" placeholder="Password" required class="border rounded w-full p-2 mb-4">
            <button type="submit" class="bg-blue-500 text-white rounded w-full p-2">Login</button>
        </form>
    </div>
</body>
</html>
