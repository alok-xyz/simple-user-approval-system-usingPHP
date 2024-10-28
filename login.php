<?php
include 'config.php';
?>
<?php

session_start();
$conn = new mysqli('localhost', 'root', '', 'user_system');

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare statement to fetch user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the user's status is approved
        if ($user['status'] === 'approved') {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                $_SESSION['student_id'] = $user['id'];
                header('Location: student_dashboard.php');
                exit();
            } else {
                echo "<script>alert('Invalid email or password');</script>";
            }
        } else {
            echo "<script>alert('Your account is pending for approval. Please wait.');</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password');</script>";
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
   
    <title>Student Login</title>
</head>
<body class="bg-green-200 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-lg w-55">
        <h1 class="text-lg font-bold mb-6">Student Login</h1>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required class="border rounded w-full p-2 mb-4" />
            <input type="password" name="password" placeholder="Password" required class="border rounded w-full p-2 mb-4" />
            <button type="submit" class="bg-blue-500 text-white rounded w-full p-2">Login</button>
        </form>
        <hr>
        <p class="mt-3 text-center">Don't Have Account?? Dont Worry!<a href="signup.php"> Register Now</a></p>
        <hr>
        <p class="mt-3 text-center">ADMIN?<a href="admin_login.php"> Login</a></p>
        
    </div>
</body>
</html>
