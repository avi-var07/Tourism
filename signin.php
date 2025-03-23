<?php
session_start(); // Start session for authentication

// Database connection
$db = new mysqli('localhost', 'root', '', 'travel');

// Check connection
if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $d = date("Y-m-d H:i:s");


    // Secure query to prevent SQL injection
    $stmt = $db->prepare("SELECT password FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch user data
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Insert login entry after successful authentication
            $stmt_log = $db->prepare("INSERT INTO login (user, pass, date_time) VALUES (?, ?, ?)");
            $stmt_log->bind_param("sss", $email, $row['password'], $d);
            $stmt_log->execute();

            $_SESSION['username'] = $email; // Set session
            header("Location: mainPage.html");
            exit();
        }
    }
    header("Location: signin.php?error=invalid"); // Redirect on failure
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 flex justify-center items-center min-h-screen pt-16">
    
<nav class="bg-gray-800 p-4 w-full fixed top-0 left-0 shadow-lg">

<div class="container mx-auto flex justify-between items-center">
    <a href="mainPage.html" class="text-white text-lg font-bold">Tour Operator</a>
    <ul class="flex space-x-6">
        <li><a href="mainPage.html" class="text-white hover:text-yellow-400">Home</a></li>
        <li><a href="destination.html" class="text-white hover:text-yellow-400">Destination</a></li>
        <!-- <li><a href="gallery.html" class="text-white hover:text-yellow-400">Gallery</a></li> -->
        <li><a href="feedback.html" class="text-white hover:text-yellow-400">Feedback</a></li>
        <li><a href="http://localhost:8080/Project/Tourism/booking.html" class=" text-white hover:text-yellow-400">Bookings</a></li>
        <li><a href="signup.php" class="text-white hover:text-yellow-400">SignUp/Login</a></li>
    </ul>
</div>
</nav>
    <div class="bg-white p-6 rounded-lg shadow-lg w-[400px] ">
        <h2 class="text-2xl font-bold mb-4 text-center">Sign In</h2>

        <?php if (isset($_GET['error']) && $_GET['error'] == "invalid") { ?>
            <script>alert('Invalid email or password');</script>
        <?php } ?>

        <form action="signin.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="flex justify-between items-center mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-gray-600">Remember Me</span>
                </label>
                <a href="forgot_password.php" class="text-blue-500 text-sm">Forgot Password?</a>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Sign In</button>
        </form>
        <p class="mt-4 text-center text-gray-600">
            Don't have an account? <a href="signup.php" class="text-blue-500">Sign Up</a>
        </p>
    </div>
</body>
</html>
