<?php
// Connect to the database
$conn = mysqli_connect("localhost", "username", "password", "database");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the form data
$room_type = $_POST['roomtype'];
$room_size = $_POST['roomsize'];
$bed_type = $_POST['bedtype'];
$smoking = $_POST['smoking'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$guest_name = $_POST['guest_name'];
$guest_email = $_POST['guest_email'];
$guest_phone = $_POST['guest_phone'];

// Calculate the number of days
$days = date_diff(date_create($checkin), date_create($checkout))->days;

// Get the room rate from the database
$sql = "SELECT price FROM rooms WHERE room_type = '$room_type' AND room_size = '$room_size' AND bed_type = '$bed_type' AND smoking = '$smoking'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$room_rate = $row['price'];

// Calculate the total cost
$total_cost = $days * $room_rate;

// Insert the data into the database
$sql = "INSERT INTO bookings (room_id, checkin, checkout, guest_name, guest_email, guest_phone, total_cost)
VALUES ((SELECT id FROM rooms WHERE room_type = '$room_type' AND room_size = '$room_size' AND bed_type = '$bed_type' AND smoking = '$smoking'), '$checkin', '$checkout', '$guest_name', '$guest_email', '$guest_phone', '$total_cost')";

if (mysqli_query($conn, $sql)) {
    echo "Booking successful! Your total cost is: $" . $total_cost;
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>