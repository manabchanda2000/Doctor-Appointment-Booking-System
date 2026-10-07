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

    // Step 1: Get appointment + clinic fee + confirmation status
    $query = "SELECT a.*, c.fees 
              FROM appointment a 
              JOIN doctor_clinic c ON a.clinic_id = c.clinic_id 
              WHERE a.appointment_id = '$appointment_id' AND a.doctor_id = '$doctor_id'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        // Check if already approved
        if ($row['doctor_confirmation'] === 'Approved') {
            echo "
            <script>
                alert('Appointment already approved!');
                window.location.href = 'doctor-appointment.php';
            </script>";
            exit();
        }
        if ($row['doctor_confirmation'] === 'Rejected') {
            echo "
            <script>
                alert('Appointment already rejected!');
                window.location.href = 'doctor-appointment.php';
            </script>";
            exit();
        }

        // Not approved yet → Proceed
        $patient_id = $row['patient_id'];
        $clinic_fee = $row['fees'];
        $payment_mode = $row['payment_mode'];

        // Step 2: Update appointment
        $update_appointment = "UPDATE appointment SET doctor_confirmation = 'Approved' WHERE appointment_id = '$appointment_id'";
        mysqli_query($conn, $update_appointment);

        // Step 3: Insert into transaction
        $insert_payment = "INSERT INTO transaction (
            appointment_id, patient_id, doctor_id, amount, payment_mode, payment_status
        ) VALUES (
            '$appointment_id', '$patient_id', '$doctor_id', '$clinic_fee', '$payment_mode', 'Pending'
        )";

        if (mysqli_query($conn, $insert_payment)) {
            echo "
            <script>
                alert('Appointment approved and payment entry created!');
                window.location.href = 'doctor-appointment.php';
            </script>";
        } else {
            echo "Error inserting payment: " . mysqli_error($conn);
        }

    } else {
        echo "Invalid appointment or unauthorized access.";
    }
} else {
    echo "No appointment selected.";
}
?>