<?php
include '../connection.php'; // Database connection
session_start();

// Suppose: ami dhore nichi jodi admin login thake admin_id nibe, dr login thake doctor_id nibe
$admin_id = $_SESSION['admin_id'] ?? NULL;
$doctor_id = $_SESSION['doctor_id'] ?? NULL;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $posted_for = mysqli_real_escape_string($conn, $_POST['posted_for']);

    $posted_by = $admin_id ? 'admin' : 'doctor';

    // Handle File Upload (optional)
    $file_name = NULL;
    if (!empty($_FILES['file']['name'])) {
        $file_name = 'notice_file' . time() . '_' . basename($_FILES['file']['name']);
        $file_target = "../upload/doctor/" . $file_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $file_target);
    }

    // Handle Image Upload (optional)
    $image_name = NULL;
    if (!empty($_FILES['image']['name'])) {
        $image_name = 'notice_file' .  time() . '_' . basename($_FILES['image']['name']);
        $image_target = "../upload/doctor/" . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_target);
    }

    // Insert Announcement
    $insert = "INSERT INTO announcement 
                (admin_id, doctor_id, title, description, category, image, file, posted_by, posted_for)
               VALUES 
                (" . ($admin_id ? "'$admin_id'" : "NULL") . ",
                 " . ($doctor_id ? "'$doctor_id'" : "NULL") . ",
                 '$title', '$description', '$category', 
                 " . ($image_name ? "'$image_name'" : "NULL") . ",
                 " . ($file_name ? "'$file_name'" : "NULL") . ",
                 '$posted_by', '$posted_for')";

    if (mysqli_query($conn, $insert)) {
        $_SESSION['success'] = "Announcement posted successfully!";
        header("Location: doctor-announcements.php"); // form submit hoye kon page e back jabe seta dao
        exit();
    } else {
        $_SESSION['error'] = "Failed to post announcement!";
        header("Location: doctor-announcements.php");
        exit();
    }
} else {
    echo "Invalid Request!";
}
?>
