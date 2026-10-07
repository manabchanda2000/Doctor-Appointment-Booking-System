<?php
session_start();
include '../connection.php'; // DB connection

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Get appointment & check if already rejected
    $query = "SELECT * FROM appointment 
              WHERE appointment_id = '$appointment_id' AND doctor_id = '$doctor_id'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['doctor_confirmation'] === 'Rejected') {
            echo "
            <script>
                alert('Appointment already rejected!');
                window.location.href = 'doctor-appointment.php';
            </script>";
            exit();
        }
        if ($row['doctor_confirmation'] === 'Approved') {
            echo "
            <script>
                alert('Appointment already Approved!');
                window.location.href = 'doctor-appointment.php';
            </script>";
            exit();
        }

        // Update appointment to rejected
        $reason = 'Doctor is unavailable at the selected slot.';
        $update_query = "UPDATE appointment 
                         SET doctor_confirmation = 'Rejected', status = 'Cancelled', cancellation_reason = '$reason' 
                         WHERE appointment_id = '$appointment_id'";
        
        if (mysqli_query($conn, $update_query)) {
            echo "
            <script>
                alert('Appointment rejected successfully!');
                window.location.href = 'doctor-appointment.php';
            </script>";
        } else {
            echo "Error updating appointment: " . mysqli_error($conn);
        }

    } else {
        echo "Invalid appointment or unauthorized access.";
    }

} else {
    echo "No appointment selected.";
}
?>
