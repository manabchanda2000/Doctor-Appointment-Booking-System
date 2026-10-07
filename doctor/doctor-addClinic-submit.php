<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.html");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Sanitize input
$clinic_name     = trim($_POST['clinic_name']);
$phone           = trim($_POST['phone']);
$email           = trim($_POST['email']);
$area            = trim($_POST['area']);
$city            = trim($_POST['city']);
$state           = trim($_POST['state']);
$pincode         = trim($_POST['pincode']);
$latitude        = $_POST['latitude'];
$longitude       = $_POST['longitude'];
$location_link   = trim($_POST['location_link']);
$days_available  = trim($_POST['days_available']);
$status          = $_POST['status'];
$opening_time    = $_POST['opening_time'];
$closing_time    = $_POST['closing_time'];
$fees            = $_POST['fees'];

// --- Handle image upload ---
$clinic_img = null;
if (isset($_FILES['clinic_img']) && $_FILES['clinic_img']['error'] === UPLOAD_ERR_OK) {
    $img_tmp = $_FILES['clinic_img']['tmp_name'];
    $img_ext = pathinfo($_FILES['clinic_img']['name'], PATHINFO_EXTENSION);
    $clinic_img = 'clinic_' . time() . '.' . $img_ext;
    move_uploaded_file($img_tmp, '../upload/doctor/' . $clinic_img);
}

// --- Insert Query ---
$sql = "INSERT INTO doctor_clinic (
    doctor_id, clinic_name, phone, email, area, city, state, pincode,
    latitude, longitude, location_link, clinic_img,
    days_available, opening_time, closing_time, fees, status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("isssssssdssssssds",
    $doctor_id, $clinic_name, $phone, $email, $area, $city, $state, $pincode,
    $latitude, $longitude, $location_link, $clinic_img,
    $days_available, $opening_time, $closing_time, $fees, $status
);

if ($stmt->execute()) {
    echo "<script>alert('Clinic added successfully'); window.location.href='doctor-clinic.php';</script>";
} else {
    echo "<script>alert('Something went wrong. Try again.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
