<?php
session_start();
include '../connection.php';

if (isset($_GET['clinic_id'])) {
    $clinic_id = $_GET['clinic_id'];

    // Delete doctor from database
    $sql = "DELETE FROM doctor_clinic WHERE clinic_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $clinic_id);

    if ($stmt->execute()) {
        echo "<script>alert('Clinic deleted successfully!'); window.location.href='admin-manageClinic.php';</script>";
    } else {
        echo "<script>alert('Error deleting doctor!'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request!'); window.history.back();</script>";
}
?>
