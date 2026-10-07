<?php
session_start();
include '../connection.php';

// Check if user is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: patient-login.php");
    exit();
}

// Sanitize and receive data
$patient_id = $_SESSION['patient_id'];
$category   = mysqli_real_escape_string($conn, $_POST['specialty']);
$title      = mysqli_real_escape_string($conn, $_POST['question']);
$desc       = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';

// Merge title + description into full question

// Insert into database
$insert = "INSERT INTO question (patient_id, specialty, question, description) 
           VALUES ('$patient_id', '$category', '$title', '$desc')";

if (mysqli_query($conn, $insert)) {
    echo "<script>alert('Question submitted successfully!'); window.location.href='patient-qna.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
