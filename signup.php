<?php
session_start();
// Database Connection
$con = mysqli_connect('localhost', 'root', '', 'travel');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $firstname = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $dob = $_POST['dob'];
    $pincode = trim($_POST['pincode']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Terms & Conditions validation
    if (!isset($_POST['terms'])) {
        $_SESSION['error'] = "You must agree to the Terms and Conditions!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
    } else {
        // Check if email already exists
        $email_check_query = "SELECT * FROM `customer` WHERE email=?";
        $stmt = mysqli_prepare($con, $email_check_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['error'] = "Email already registered!";
        } else {
            // Hash Password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert Data
            $insert = "INSERT INTO `customer` (fname, email, phone, dob, pincode, city, state, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $insert);
            mysqli_stmt_bind_param($stmt, "ssssssss", $firstname, $email, $phone, $dob, $pincode, $city, $state, $hashed_password);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Signup Successful! Please login.";
                header("Location: signin.php");
                exit();
            } else {
                $_SESSION['error'] = "Signup failed. Please try again!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function fetchLocation() {
            let pincode = document.getElementById("pincode").value;
            let cityField = document.getElementById("city");
            let stateField = document.getElementById("state");

            if (pincode.length === 6) {
                fetch(`https://api.postalpincode.in/pincode/${pincode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data[0].Status === "Success") {
                            cityField.value = data[0].PostOffice[0].District;
                            stateField.value = data[0].PostOffice[0].State;
                        } else {
                            cityField.value = "";
                            stateField.value = "";
                            alert("Invalid Pincode! Please enter a valid one.");
                        }
                    })
                    .catch(error => console.error("Error fetching location:", error));
            }
        }

        function validateTerms() {
            if (!document.getElementById("terms").checked) {
                alert("You must agree to the Terms and Conditions to sign up.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-lg w-[400px]">
        <h2 class="text-2xl font-bold mb-4 text-center">Create an Account</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='text-red-500 text-center mb-4'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<p class='text-green-500 text-center mb-4'>".$_SESSION['success']."</p>";
            unset($_SESSION['success']);
        }
        ?>

        <form action="signup.php" method="POST" onsubmit="return validateTerms()">
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-gray-700">Full Name</label>
                    <input type="text" name="name" required 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="col-span-2">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" required 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="col-span-2">
                    <label class="block text-gray-700">Contact Number</label>
                    <input type="tel" name="phone" pattern="[0-9]{10}" maxlength="10" required 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                           placeholder="Enter 10-digit number">
                </div>

                <div>
                    <label class="block text-gray-700">DOB</label>
                    <input type="date" name="dob" required 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700">Pincode</label>
                    <input type="text" id="pincode" name="pincode" required maxlength="6" pattern="[0-9]{6}"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                           placeholder="Enter Pincode" onkeyup="fetchLocation()">
                </div>

                <div>
                    <label class="block text-gray-700">City</label>
                    <input type="text" id="city" name="city" readonly 
                           class="w-full px-3 py-2 border rounded-lg bg-gray-200">
                </div>

                <div>
                    <label class="block text-gray-700">State</label>
                    <input type="text" id="state" name="state" readonly 
                           class="w-full px-3 py-2 border rounded-lg bg-gray-200">
                </div>

                <div class="col-span-2">
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" required 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="col-span-2">
                    <label class="block text-gray-700">Confirm Password</label>
                    <input type="password" name="confirm_password" required 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="col-span-2 flex items-center">
                    <input type="checkbox" id="terms" name="terms" class="mr-2">
                    <label for="terms" class="text-gray-700 text-sm">I agree to the 
                        <a href="terms.php" class="text-blue-500 underline">Terms and Conditions</a>
                    </label>
                </div>
            </div>

            <button type="submit" name="signup"
                    class="w-full mt-4 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Sign Up
            </button>
            <p class="text-center text-gray-700 mt-3">Already have an account? 
                <a href="signin.php" class="text-blue-500 underline">Login</a>
            </p>

        </form>
    </div>
</body>
</html>
