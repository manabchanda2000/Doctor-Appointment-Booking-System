<?php
include '../connection.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get all inputs
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);

    // File Upload handling
    $prescription_filename = null;

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = "../upload/patient/";
        
        // Folder create if not exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $original_name = basename($_FILES['file']['name']);
        $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);
        $new_filename = 'prescription_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $prescription_filename = $new_filename;
        } else {
            echo "<script>alert('File upload failed!'); window.history.back();</script>";
            exit;
        }
    }

    // Insert into database
    $query = "INSERT INTO prescription (appointment_id, patient_id, doctor_id, prescription) 
              VALUES ('$appointment_id', '$patient_id', '$doctor_id', '$prescription_filename')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Prescription added successfully!'); window.location.href = 'doctor-prescription.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid Request'); window.location.href = 'doctor-prescription.php';</script>";
}
?>
