<?php
include 'config.php';
?>
<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'user_system');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $photo = $_FILES['photo']['name']; // Assuming you handle file uploads

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Move uploaded file to desired directory
    move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $photo);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, photo, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $photo);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Waiting for approval from HOD.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Registration failed. Please try again.');</script>";
    }
    $stmt->close();
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
    <title>User Signup</title>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h1 class="text-lg font-bold mb-6">User Signup</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="full_name" placeholder="Full Name" required class="border rounded w-full p-2 mb-4" />
            <input type="email" name="email" placeholder="Email" required class="border rounded w-full p-2 mb-4" />
            <input type="password" name="password" placeholder="Password" required class="border rounded w-full p-2 mb-4" />
            <input type="file" name="photo" required class="border rounded w-full p-2 mb-4" />
            <button type="submit" class="bg-blue-500 text-white rounded w-full p-2">Sign Up</button>
            <p class="mt-3 text-center">Alredy have an account? <a href="login.php">Login </a></p>
        </form>
    </div>
</body>
</html>
