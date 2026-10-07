<?php
session_start();
include '../connection.php';

if (!isset($_GET['id'])) {
    echo "Invalid Access";
    exit();
}

$appointment_id = $_GET['id'];

$query = "SELECT 
    a.*, 
    d.name AS doctor_name,
    d.specialization,
    d.gender AS doctor_gender,
    d.profile_img AS doctor_img,
    p.name AS patient_name,
    p.gender AS patient_gender,
    p.dob,
    p.blood_group,
    p.phone AS patient_phone,
    p.profile_img AS patient_img,
    c.clinic_name,
    c.city,
    c.state,
    c.area,
    c.pincode,
    c.email AS clinic_email,
    c.phone AS clinic_phone,
    pay.payment_status,
    pay.payment_mode,
    pay.transaction_date,
    pay.amount,
    pay.transaction_id
FROM appointment a
JOIN doctor d ON a.doctor_id = d.doctor_id
JOIN patient p ON a.patient_id = p.patient_id
JOIN doctor_clinic c ON a.clinic_id = c.clinic_id
LEFT JOIN transaction pay ON a.appointment_id = pay.appointment_id
WHERE a.appointment_id = '$appointment_id'
";


$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Appointment not found.";
    exit();
}

$app = mysqli_fetch_assoc($result);
$dob = new DateTime($app['dob']);
$age = (new DateTime())->diff($dob)->y;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Appointment Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
    body {
        font-family: 'Inter', sans-serif;
        background: #e7f0fb;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 900px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
        border-bottom: 2px solid #3B82F6;
        padding-bottom: 5px;
    }

    .section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #d0d7e3;
    }

    .inline-fields {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
    }

    .inline-fields p {
        margin: 6px 0;
    }

    label {
        font-weight: 600;
        color: #555;
        margin-right: 6px;
    }

    .profile-img {
        height: 80px;
        width: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        border: 3px solid #3B82F6;
    }

    .grid {
        display: flex;
        gap: 40px;
        justify-content: space-around;
    }

    .grid .section {
        flex: 1;
    }

    h3 {
        margin-bottom: 10px;
        color: #2563eb;
        font-size: 18px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Appointment Details</h2>

        <div class="section inline-fields">
            <p><label>Appointment ID:</label> <?= $app['appointment_id'] ?></p>
            <p><label>Date:</label> <?= date("M d, Y", strtotime($app['appointment_date'])) ?></p>
            <p><label>Time:</label> <?= date("h:i A", strtotime($app['appointment_time'])) ?></p>
            <p><label>Status:</label> <?= $app['status'] ?></p>
            <p><label>Doctor Confirmation:</label> <?= $app['doctor_confirmation'] ?></p>
            <p><label>Cancellation Reason:</label> <?= $app['cancellation_reason'] ?: 'N/A'?></p>
        </div>

        <div class="section grid">
            <div>
                <h3>Doctor Info</h3>
                <img src="../upload/doctor/<?= $app['doctor_img'] ?>" class="profile-img">
                <p><label>Name:</label> <?= $app['doctor_name'] ?></p>
                <p><label>Gender:</label> <?= $app['doctor_gender'] ?></p>
                <p><label>Speciality:</label> <?= $app['specialization'] ?></p>
            </div>
            <div>
                <h3>Patient Info</h3>
                <img src="../upload/patient/<?= $app['patient_img'] ?>" class="profile-img">
                <p><label>Name:</label> <?= $app['patient_name'] ?></p>
                <p><label>Age/Gender:</label> <?= $age . ' / ' . $app['patient_gender'] ?></p>
                <p><label>Blood Group:</label> <?= $app['blood_group'] ?></p>
                <p><label>Phone:</label> <?= $app['patient_phone'] ?></p>
            </div>
        </div>

        <div class="section">
            <h3>Clinic Info</h3>
            <p><label>Clinic:</label> <?= $app['clinic_name'] ?></p>
            <p><label>Location:</label>
                <?= $app['area'] . ', ' . $app['city'] . ', ' . $app['state'] . ' - ' . $app['pincode'] ?></p>
            <p><label>Contact:</label> <?= $app['clinic_email'] . ' / ' . $app['clinic_phone'] ?></p>
        </div>

        <div class="section">
            <h3>Payment Info</h3>
            <p><label>Status:</label> <?= $app['payment_status'] ?? 'N/A'?></p>
            <p><label>Mode:</label> <?= $app['payment_mode'] ?? 'N/A' ?></p>
            <p><label>Date:</label>
                <?= !empty($app['transaction_date']) ? date("M d, Y, h:i A", strtotime($app['transaction_date'])) : 'N/A' ?>
            </p>
            <p><label>Amount:</label> ₹<?= $app['amount'] ?? 'N/A' ?></p>
        </div>
    </div>
</body>

</html>