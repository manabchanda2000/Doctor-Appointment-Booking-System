<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clinic_id = $_POST['patient_id'];
    $status = $_POST['availability'];



    // Update doctor availability in the database
    $sql = "UPDATE patient SET availability = ? WHERE patient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $clinic_id);

    if ($stmt->execute()) {
        echo "<script>alert('Patient updated successfully!'); window.location.href='admin-managePatient.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method!";
}
?>
