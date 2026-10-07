<?php
include 'connection.php'; // Database connection file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Prepare SQL query
    $sql = "INSERT INTO query (name, email, phone, message, created_at) 
            VALUES (?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    
    // Check if prepare() was successful
    if ($stmt === false) {
        die("Error in SQL Query: " . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Form submitted successfully!!'); window.location.href='contact.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement & connection
    $stmt->close();
    $conn->close();
}
?>
