<?php
session_start();
include '../connection.php';

// Check login
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.html");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Check if clinic_id is set
if (!isset($_GET['clinic_id'])) {
    echo "<script>alert('No clinic selected to delete.'); window.location.href='doctor-clinic.php';</script>";
    exit();
}

$clinic_id = intval($_GET['clinic_id']);

// Optional: Fetch and delete old image (if needed)
$getImage = $conn->prepare("SELECT clinic_img FROM doctor_clinic WHERE clinic_id = ? AND doctor_id = ?");
$getImage->bind_param("ii", $clinic_id, $doctor_id);
$getImage->execute();
$result = $getImage->get_result();
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    if (!empty($data['clinic_img']) && file_exists("../upload/doctor/" . $data['clinic_img'])) {
        unlink("../upload/doctor/" . $data['clinic_img']);
    }
}
$getImage->close();

// Delete clinic
$delete = $conn->prepare("DELETE FROM doctor_clinic WHERE clinic_id = ? AND doctor_id = ?");
$delete->bind_param("ii", $clinic_id, $doctor_id);

if ($delete->execute()) {
    echo "<script>alert('Clinic deleted successfully.'); window.location.href='doctor-clinic.php';</script>";
} else {
    echo "<script>alert('Failed to delete clinic.'); window.history.back();</script>";
}

$delete->close();
$conn->close();
?>
