<?php
include '../connection.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get Prescription ID
    $prescription_id = mysqli_real_escape_string($conn, $_POST['prescription_id']);

    // File Upload handling
    $updated_filename = null;

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = "../upload/patient/";
        
        // Create folder if it does not exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Generate new unique file name
        $original_name = basename($_FILES['file']['name']);
        $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);
        $new_filename = 'prescription_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $updated_filename = $new_filename;
        } else {
            echo "<script>alert('File upload failed!'); window.history.back();</script>";
            exit;
        }
    }

    // Update prescription in the database
    $query = "UPDATE prescription SET prescription = '$updated_filename' WHERE prescription_id = '$prescription_id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Prescription updated successfully!'); window.location.href = 'doctor-prescription.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid Request'); window.history.back();</script>";
}
?>
