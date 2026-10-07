<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.html");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];
$clinic_id = $_POST['clinic_id'];

// Sanitize inputs
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

// Get existing image from DB
$query = "SELECT clinic_img FROM doctor_clinic WHERE clinic_id = ? AND doctor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $clinic_id, $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$oldData = $result->fetch_assoc();
$oldImage = $oldData['clinic_img'] ?? null;

// Image handling
$clinic_img = $oldImage;
if (isset($_FILES['clinic_img']) && $_FILES['clinic_img']['error'] === UPLOAD_ERR_OK) {
    $img_tmp = $_FILES['clinic_img']['tmp_name'];
    $img_ext = pathinfo($_FILES['clinic_img']['name'], PATHINFO_EXTENSION);
    $clinic_img = 'clinic_' . time() . '.' . $img_ext;
    $upload_path = '../upload/doctor/' . $clinic_img;

    if (move_uploaded_file($img_tmp, $upload_path)) {
        // Delete old image if it exists
        if ($oldImage && file_exists('../upload/doctor/' . $oldImage)) {
            unlink('../upload/doctor/' . $oldImage);
        }
    } else {
        echo "<script>alert('Image upload failed.'); window.history.back();</script>";
        exit();
    }
}

// Update query
$sql = "UPDATE doctor_clinic SET 
    clinic_name = ?, phone = ?, email = ?, area = ?, city = ?, state = ?, pincode = ?, 
    latitude = ?, longitude = ?, location_link = ?, clinic_img = ?, 
    days_available = ?, opening_time = ?, closing_time = ?, fees = ?, status = ?
    WHERE clinic_id = ? AND doctor_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "ssssssddssssssdsii",
    $clinic_name, $phone, $email, $area, $city, $state, $pincode,
    $latitude, $longitude, $location_link, $clinic_img,
    $days_available, $opening_time, $closing_time, $fees, $status,
    $clinic_id, $doctor_id
);


if ($stmt->execute()) {
    echo "<script>alert('Clinic updated successfully'); window.location.href='doctor-clinic.php';</script>";
} else {
    echo "<script>alert('Something went wrong during update.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>