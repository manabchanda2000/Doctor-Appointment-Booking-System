<?php
session_start();
include '../connection.php';

if (!isset($_GET['id'])) {
    echo "Invalid Access!";
    exit();
}

$appointment_id = $_GET['id'];

// Fetch appointment status and dr_confirmation
$query = "SELECT status, doctor_confirmation FROM appointment WHERE appointment_id = '$appointment_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Appointment not found!";
    exit();
}

$row = mysqli_fetch_assoc($result);
$status = $row['status'];
$dr_confirmation = $row['doctor_confirmation'];

// Check if appointment is already cancelled/completed
if ($status === 'Cancelled' || $status === 'Completed') {
    echo "<script>alert('This appointment is already $status. You cannot cancel it.'); window.history.back();</script>";
    exit();
}

// Check if doctor confirmation is already approved or rejected
if ($dr_confirmation === 'Approved' || $dr_confirmation === 'Rejected') {
    echo "<script>alert('Doctor has already $dr_confirmation this appointment. You cannot cancel it now.'); window.history.back();</script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cancel Appointment</title>
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f0f4f8;
        padding: 50px 20px;
    }

    .form-container {
        max-width: 450px;
        margin: auto;
        background: #ffffff;
        padding: 35px 30px;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    label {
        font-size: 15px;
        font-weight: 600;
        color: #444;
        margin-bottom: 8px;
        display: block;
    }

    select {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #ccc;
        background-color: #fff;
        font-size: 15px;
    }

    button {
        width: 100%;
        padding: 14px;
        background: #e53935;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        cursor: pointer;
        margin-top: 25px;
        transition: 0.3s ease;
    }

    button:hover {
        background: #c62828;
    }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>Cancel Appointment</h2>
        <form action="patient-cancelAppointment-submit.php" method="POST">
            <input type="hidden" name="appointment_id" value="<?= $appointment_id ?>">

            <label for="cancel_reason">Select a reason</label>
            <select name="cancel_reason" required>
                <option value="">-- Choose a reason --</option>
                <option value="Not feeling well enough to visit">Not feeling well enough</option>
                <option value="Found another slot">Found another slot</option>
                <option value="Changed my mind">Changed my mind</option>
                <option value="Doctor not available">Doctor not available</option>
                <option value="Other personal reasons">Other personal reasons</option>
            </select>

            <button type="submit">Submit Cancellation</button>
        </form>
    </div>

</body>

</html>