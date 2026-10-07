<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clinic_id = $_POST['clinic_id'];
    $status = $_POST['status'];



    // Update doctor availability in the database
    $sql = "UPDATE doctor_clinic SET status = ? WHERE clinic_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $clinic_id);

    if ($stmt->execute()) {
        echo "<script>alert('Clinic updated successfully!'); window.location.href='admin-manageClinic.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method!";
}
?>
