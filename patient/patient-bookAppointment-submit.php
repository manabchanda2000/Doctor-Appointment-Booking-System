<?php
session_start();
include '../connection.php'; // DB connection

// Check if patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: patient-login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Get values from form
$doctor_id = $_POST['doctor_id'];
$clinic_id = $_POST['clinic_id'];
$appointment_date = $_POST['appointment_date'];
$appointment_time = $_POST['appointment_time'];
$mode_of_appointment = $_POST['mode_of_appointment'];
$payment_mode = $_POST['payment_mode'];

// Prepare insert query
$query = "INSERT INTO appointment (
    patient_id, doctor_id, clinic_id, appointment_date, appointment_time, 
    mode_of_appointment, payment_mode
) VALUES (
    '$patient_id', '$doctor_id', '$clinic_id', '$appointment_date', 
    '$appointment_time', '$mode_of_appointment', '$payment_mode'
)";

if (mysqli_query($conn, $query)) {
    // JS Alert and redirect
    echo "
<script>
    if (confirm('🎉 Appointment Booked Successfully!\\nWait for doctor\\'s confirmation. Proceed to your appointments?')) {
        window.location.href = 'patient-appointment.php';
    }
</script>
";

    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}
?>