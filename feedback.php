<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "travel";
$port = 3306; // MySQL port in XAMPP

$conn = new mysqli($host, $user, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guide_fname = $_POST["guide_fname"];
    $trip_name = $_POST["trip_name"];
    $trip_destination = $_POST["trip_destination"];
    $departure_date = $_POST["departure_date"];
    
    // Ratings
    $accommodation_rating = $_POST["accommodation"] ?? "Not Rated";
    $transport_rating = $_POST["transport"] ?? "Not Rated";
    $food_rating = $_POST["food"] ?? "Not Rated";
    $places_rating = $_POST["places"] ?? "Not Rated";
    $professionalism_rating = $_POST["professionalism"] ?? "Not Rated";
    $costs_rating = $_POST["costs"] ?? "Not Rated";
    $communication_rating = $_POST["ease_of_communication"] ?? "Not Rated";
    $safety_rating = $_POST["safety"] ?? "Not Rated";
    $driver_rating = $_POST["driver"] ?? "Not Rated";
    $tour_guide_rating = $_POST["tour_guide"] ?? "Not Rated";
    $knowledge_rating = $_POST["knowledge"] ?? "Not Rated";
    $registration_rating = $_POST["registration_process"] ?? "Not Rated";
    $payment_rating = $_POST["payment_process"] ?? "Not Rated";

    // Overall rating
    $overall_rating = isset($_POST["overall_rating"]) ? floatval($_POST["overall_rating"]) : 0;

    // Text fields
    $places_enjoyed = $_POST["places_enjoyed"];
    $places_not_enjoyed = $_POST["places_not_enjoyed"];
    $places_next = $_POST["places_next"];
    $heard_about_us = $_POST["heard_about_us"];
    $additional_feedback = $_POST["additional_feedback"];
    $recommend = $_POST["recommend"] ?? "No";
    $promo_emails = $_POST["promo_emails"] ?? "No";

    // Insert into database
    $sql = "INSERT INTO feedback (
                guide_fname, trip_name, trip_destination, departure_date,
                accommodation_rating, transport_rating, food_rating, places_rating,
                professionalism_rating, costs_rating, communication_rating, safety_rating,
                driver_rating, tour_guide_rating, knowledge_rating, registration_rating, payment_rating,
                overall_rating, places_enjoyed, places_not_enjoyed, places_next,
                heard_about_us, additional_feedback, recommend, promo_emails
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssssssdsssssss",
        $guide_fname, $trip_name, $trip_destination, $departure_date,
        $accommodation_rating, $transport_rating, $food_rating, $places_rating,
        $professionalism_rating, $costs_rating, $communication_rating, $safety_rating,
        $driver_rating, $tour_guide_rating, $knowledge_rating, $registration_rating, $payment_rating,
        $overall_rating, $places_enjoyed, $places_not_enjoyed, $places_next,
        $heard_about_us, $additional_feedback, $recommend, $promo_emails
    );

    if ($stmt->execute()) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='feedback.html';</script>";
    } else {
        echo "<script>alert('Error submitting feedback!'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>
