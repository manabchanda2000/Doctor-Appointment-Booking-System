<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $cancel_reason = mysqli_real_escape_string($conn, $_POST['cancel_reason']);

    $query = "UPDATE appointment 
              SET status = 'Cancelled', 
                  doctor_confirmation = 'Rejected', 
                  cancellation_reason = '$cancel_reason' 
              WHERE appointment_id = '$appointment_id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Appointment cancelled successfully.');
            window.location.href = 'patient-appointment.php'; // update path if needed
        </script>";
    } else {
        echo "Failed to cancel appointment. Try again.";
    }
}
?>
