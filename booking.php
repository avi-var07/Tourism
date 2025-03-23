<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST["ffirst"]);
    $last_name = trim($_POST["flast"]);
    $email = trim($_POST["femail"]);
    $pincode = trim($_POST["pincode"]);
    $city = trim($_POST["city"]);
    $state = trim($_POST["state"]);
    $phone = trim($_POST["fphone"]);
    $destination = trim($_POST["fdesti"]);

    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($pincode) || empty($city) || empty($state) || empty($phone) || empty($destination)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit;
    }

    // Insert Data into Database
    $stmt = $conn->prepare("INSERT INTO bookings (first_name, last_name, email, pincode, city, state, phone, destination) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $first_name, $last_name, $email, $pincode, $city, $state, $phone, $destination);

    if ($stmt->execute()) {
        echo "<script>alert('Booking Successful!'); window.location.href='mainPage.html';</script>";
    } else {
        echo "<script>alert('Error occurred! Please try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
