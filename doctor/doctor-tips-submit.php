<?php
session_start();
include '../connection.php';

// Check doctor login
if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];
$title = mysqli_real_escape_string($conn, $_POST['title']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$category = mysqli_real_escape_string($conn, $_POST['category']);

$image_path = '';

// Image upload handling
if (!empty($_FILES['image']['name'])) {
    $image_name = 'tips_' . time() . '_' . basename($_FILES['image']['name']);
    $target_dir = '../upload/doctor/';
    $target_file = $target_dir . $image_name;

    // Make sure upload dir exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $image_path = $image_name;
    }
}

// Insert into health_tips table
$sql = "INSERT INTO health_tips (doctor_id, title, description, image, category)
        VALUES ('$doctor_id', '$title', '$description', '$image_path', '$category')";

if (mysqli_query($conn, $sql)) {
    header("Location: doctor-healthtips.php");
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
