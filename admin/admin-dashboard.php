<?php
include '../connection.php';
session_start();

// Verify user authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.html");
    exit();
}

$admin_id = $_SESSION['admin_id'];
// Fetch the last login time and last logout time
$query = "SELECT logout_time FROM log_info WHERE admin_id = $admin_id ORDER BY logout_time DESC LIMIT 1";
$result = $conn->query($query);
$last_logout = "Not available"; // Default value

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (!empty($row['logout_time'])) {
        $last_logout = date('d/m/Y h:i A', strtotime($row['logout_time']));
    }
}

// Queries
$patientQuery = "SELECT COUNT(DISTINCT patient_id) AS patient_count FROM patient";
$doctorQuery = "SELECT COUNT(DISTINCT doctor_id) AS doctor_count FROM doctor";
$appointmentQuery = "SELECT COUNT(*) AS completed_appointments FROM appointment WHERE status = 'completed'";
$clinicQuery = "SELECT COUNT(*) AS clinic_count FROM doctor_clinic";
$emergencyQuery = "SELECT COUNT(*) AS completed_emergencies FROM emergency_booking WHERE status = 'completed'";
$reviewQuery = "SELECT rating AS average_rating FROM admin where admin_id = $admin_id";
$incomeQuery = "SELECT SUM(amount) AS total_income FROM transaction WHERE payment_status = 'Paid'";
$queryQuery = "SELECT COUNT(*) AS total_queries FROM query";

// Execute queries and fetch results
$patientResult = $conn->query($patientQuery)->fetch_assoc();
$doctorResult = $conn->query($doctorQuery)->fetch_assoc();
$appointmentResult = $conn->query($appointmentQuery)->fetch_assoc();
$clinicResult = $conn->query($clinicQuery)->fetch_assoc();
$emergencyResult = $conn->query($emergencyQuery)->fetch_assoc();
$reviewResult = $conn->query($reviewQuery)->fetch_assoc();
$incomeResult = $conn->query($incomeQuery)->fetch_assoc();
$queryResult = $conn->query($queryQuery)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: "Inter", sans-serif;
    }

    main {
        background: linear-gradient(135deg, #f2f8fc, #f0f8ff);
        padding: 80px 20px 20px 280px;
    }

    /* first section------------------------- */
    .first-sec {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        padding: 10px;
        margin-bottom: 20px;
    }

    .welcome h2 {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .welcome span {
        color: #4b5563;
        font-size: 16px;
    }

    /* second section------------------------ */
    .second-sec {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .count-part {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .count-box {
        flex: 0 0 200px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        padding: 20px;
        position: relative;
        overflow: hidden;
    }

    .count-box .icon {
        font-size: 12px;
        padding: 10px;
        border-radius: 12px;
        display: inline-block;
        margin-bottom: 10px;
        color: #fff;
        position: relative;
        z-index: 1;
    }

    .count-box h2 {
        font-size: 24px;
        margin: 5px 0;
        color: #111827;
    }

    .count-box p {
        font-size: 14px;
        color: #6b7280;
    }

    .count-box .bg-circle {
        position: absolute;
        top: -55px;
        right: -55px;
        width: 132px;
        height: 132px;
        border-radius: 50%;
        opacity: 0.15;
        z-index: 0;
    }

    .quick-action {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 15px;
        gap: 10px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        border-radius: 10px;
        flex: 0 0 280px;
    }

    .quick-action h3 {
        font-size: 18px;
        margin-bottom: 15px;
    }

    .quick-action .action-box {
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 10px;
        border: 1px solid #d2d2d2;
        border-radius: 5px;
        width: 100%;
        transition: all .3s ease;
    }

    .action-box:hover {
        border: 1px solid #3b82f6;
    }

    .action-box span {
        padding: 10px;
        border-radius: 50%;
        font-size: 12px;
        color: white;
    }

    .action-box p {
        flex: 1;
        font-size: 14px;
        color: #111827;
    }

    /* third section-------------------- */
    .third-sec {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: 20px 0;
        flex-wrap: wrap;
        /* mobile responsive */
    }

    .table-box {
        flex: 1 1 500px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        min-width: 400px;
        background: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .table-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .table-head #view-btn {
        text-decoration: none;
        color: #10b981;
        padding: 5px 10px;
        font-size: 12px;
        border: 1px solid #10b981;
        border-radius: 5px;
    }

    .table {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 300px;
    }

    table {
        min-width: 600px;
        /* force horizontal scroll when needed */
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background-color: #14b8a6;
        color: white;
        position: sticky;
        top: 0;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        white-space: nowrap;
    }

    td {
        font-size: 14px;
        color: #4B5563;
    }

    tbody tr {
        background-color: #fdfdfd;
        transition: all .3s ease;
    }

    tbody tr:nth-child(even) {
        background-color: #f1f1f1;
    }

    tbody tr:hover {
        background-color: #defaf6;
        /* opacity: 0.15; */
    }

    .table img {
        height: 30px;
        width: 30px;
        border-radius: 50%;
        object-fit: fill;
    }

    .table strong {
        display: block;
        color: #141212;
    }

    .table span {
        display: block;
    }

    .table i {
        font-size: 16px;
        margin-right: 5px;
        cursor: pointer;
    }

    /* fifth section--------------------------- */
    .fifth-sec {
        margin: 20px 0;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        flex-wrap: wrap;
    }

    .emergency {
        flex: 1 1 200px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 15px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }

    .emergency-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 10px;
        border: 1px solid #d2d2d2;
        border-radius: 5px;
        transition: all .3s ease;
    }

    .emergency-box:hover {
        border: 1px solid #14b8a6;
    }

    .box-left {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 3px;
    }

    .box-left strong {
        font-size: 16px;
    }

    .box-left #type {
        color: #4B5563;
        font-size: 14px;
    }

    .box-left small {
        color: #4b5563;
        font-size: 12px;
    }

    .box-right #status {
        padding: 5px 10px;
        font-size: 14px;
        background-color: #f4e4c8;
        color: #bd7800;
        border-radius: 20px;
    }
    </style>
</head>

<body>
    <?php include 'admin-header.php'?>
    <main>
        <section class="first-sec">
            <div class="welcome">
                <h2>Welcome Admin!</h2>
                <span>Last Active: <?= $last_logout ?></span>
            </div>
        </section>

        <section class="second-sec">
            <div class="count-part">
                <div class="count-box">
                    <div class="icon" style="background-color: #3b82f6">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h2><?php echo $patientResult['patient_count']; ?></h2>
                    <p>Patients</p>
                    <div class="bg-circle" style="background-color: #3b82f6"></div>
                </div>
                <div class="count-box">
                    <div class="icon" style="background-color: #10b981">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <h2><?php echo $doctorResult['doctor_count']; ?></h2>
                    <p>Doctors</p>
                    <div class="bg-circle" style="background-color: #10b981"></div>
                </div>
                <div class="count-box">
                    <div class="icon" style="background-color: #8b5cf6">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <h2><?php echo $appointmentResult['completed_appointments']; ?></h2>
                    <p>Appointments</p>
                    <div class="bg-circle" style="background-color: #8b5cf6"></div>
                </div>
                <div class="count-box">
                    <div class="icon" style="background-color: #6366f1">
                        <i class="fa-solid fa-hospital-user"></i>
                    </div>
                    <h2><?php echo $clinicResult['clinic_count']; ?></h2>
                    <p>Clinics</p>
                    <div class="bg-circle" style="background-color: #6366f1"></div>
                </div>
                <div class="count-box">
                    <div class="icon" style="background-color: #ef4444">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h2><?php echo $emergencyResult['completed_emergencies']; ?></h2>
                    <p>Emergency</p>
                    <div class="bg-circle" style="background-color: #ef4444"></div>
                </div>
                <div class="count-box">
                    <div class="icon" style="background-color: #f59e0b">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h2><?php echo round($reviewResult['average_rating'], 1); ?></h2>
                    <p>Reviews</p>
                    <div class="bg-circle" style="background-color: #f59e0b"></div>
                </div>
                <div class="count-box">
                    <div class="icon" style="background-color: #a855f7">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                    <h2><?php echo number_format($incomeResult['total_income'], 0); ?></h2>
                    <p>Revenue</p>
                    <div class="bg-circle" style="background-color: #a855f7"></div>
                </div>
                <div class="count-box">
                    <div class="icon" style="background-color: #14b8a6">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                    <h2><?php echo $queryResult['total_queries']; ?></h2>
                    <p>Query</p>
                    <div class="bg-circle" style="background-color: #14b8a6"></div>
                </div>
            </div>
            <div class="quick-action">
                <h3>Quick Actions</h3>

                <a class="action-box" href="/admin/add-patient">
                    <span style="background-color: #3b82f6"><i class="fa-solid fa-users"></i></span>
                    <p>Add Patients</p>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

                <a class="action-box" href="/admin/add-doctor">
                    <span style="background-color: #10b981"><i class="fa-solid fa-user-doctor"></i></span>
                    <p>Add Doctors</p>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

                <a class="action-box" href="/admin/add-announcement">
                    <span style="background-color: #a855f7"><i class="fa-solid fa-bullhorn"></i></span>
                    <p>Add Announcement</p>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

                <a class="action-box" href="/admin/appointments">
                    <span style="background-color: #8b5cf6"><i class="fa-solid fa-calendar-check"></i></span>
                    <p>View Appointment</p>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

                <a class="action-box" href="/admin/add-query">
                    <span style="background-color: #14b8a6"><i class="fa-solid fa-envelope-open-text"></i></span>
                    <p>Add Queries</p>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            </div>
        </section>

        <section class="third-sec">
            <div class="table-box">
                <div class="table-head">
                    <h3>Recent Doctors</h3>
                    <a href="" id="view-btn">View All</a>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>UID</th>
                                <th>Doctor</th>
                                <th>Gender/Age</th>
                                <th>Contact</th>
                                <th>Experience</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM doctor ORDER BY created_at DESC LIMIT 5";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['UID']); ?></td>
                                <td>
                                    <img src="../upload/doctor/<?= htmlspecialchars($row['profile_img']); ?>"
                                        alt="Doctor Image">
                                    <strong><?= htmlspecialchars($row['name']); ?></strong>
                                    <span><?= htmlspecialchars($row['specialization']); ?></span>
                                </td>
                                <td>
                                    <span><?= htmlspecialchars($row['gender']); ?> /
                                        <?= date("Y") - date("Y", strtotime($row['dob'])); ?> years</span>
                                </td>
                                <td>
                                    <span><?= htmlspecialchars($row['email']); ?></span>
                                    <span><?= htmlspecialchars($row['phone']); ?></span>
                                </td>
                                <td><?= htmlspecialchars($row['experience']); ?> years</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-box">
                <div class="table-head">
                    <h3>Recent Patients</h3>
                    <a href="" id="view-btn">View All</a>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Gender/Age</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Blood Group</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM patient ORDER BY created_at DESC LIMIT 5";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td>
                                    <img src="../upload/patient/<?= htmlspecialchars($row['profile_img']); ?>"
                                        alt="Patient Image">
                                    <strong><?= htmlspecialchars($row['name']); ?></strong>
                                    <span>Id: <?= htmlspecialchars($row['patient_id']); ?></span>
                                </td>
                                <td>
                                    <span><?= htmlspecialchars($row['gender']); ?> /
                                        <?= date("Y") - date("Y", strtotime($row['dob'])); ?> years</span>
                                </td>
                                <td>
                                    <span><?= htmlspecialchars($row['email']); ?></span>
                                    <span><?= htmlspecialchars($row['phone']); ?></span>
                                </td>
                                <td>
                                    <span><?= htmlspecialchars($row['area']); ?>,
                                        <?= htmlspecialchars($row['city']); ?></span>
                                    <span><?= htmlspecialchars($row['state']); ?> -
                                        <?= htmlspecialchars($row['pincode']); ?></span>
                                </td>
                                <td><?= htmlspecialchars($row['blood_group']); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="forth-sec">
            <div class="table-box">
                <div class="table-head">
                    <h3>Recent Pending Appointments</h3>
                    <a href="" id="view-btn">View All</a>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Clinic</th>
                                <th>Appointment Date</th>
                                <th>Appointment Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sql = "SELECT a.*, p.name AS patient_name, p.profile_img AS patient_img, p.patient_id,
                                        d.name AS doctor_name, d.profile_img AS doctor_img, d.doctor_id,
                                        c.clinic_name, c.clinic_id
                                    FROM appointment a
                                    JOIN patient p ON a.patient_id = p.patient_id
                                    JOIN doctor d ON a.doctor_id = d.doctor_id
                                    JOIN doctor_clinic c ON a.clinic_id = c.clinic_id
                                    WHERE a.status = 'Scheduled'
                                    ORDER BY a.appointment_date ASC, a.appointment_time ASC
                                    LIMIT 5";

                            $result = $conn->query($sql);
                            
                                 if ($result->num_rows > 0) { // If appointments exist 
                                     while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td>
                                    <img src="../upload/<?= htmlspecialchars($row['patient_img']); ?>"
                                        alt="Patient Image">
                                    <strong><?= htmlspecialchars($row['patient_name']); ?></strong>
                                    <span>Id: <?= htmlspecialchars($row['patient_id']); ?></span>
                                </td>
                                <td>
                                    <img src="../upload/<?= htmlspecialchars($row['doctor_img']); ?>"
                                        alt="Doctor Image">
                                    <strong><?= htmlspecialchars($row['doctor_name']); ?></strong>
                                    <span>Id: <?= htmlspecialchars($row['doctor_id']); ?></span>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($row['clinic_name']); ?></strong>
                                    <span>Id: <?= htmlspecialchars($row['clinic_id']); ?></span>
                                </td>
                                <td><?= date("d/m/Y", strtotime($row['appointment_date'])); ?></td>
                                <td><?= date("h:i A", strtotime($row['appointment_time'])); ?></td>
                            </tr>
                            <?php } ?>
                            <?php } else { // If no appointments found ?>
                            <tr>
                                <td colspan="5" style="text-align:center; font-weight:bold;">No appointments found</td>
                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="fifth-sec">
            <div class="table-box">
                <div class="table-head">
                    <h3>Recent Transactions</h3>
                    <a href="" id="view-btn">View All</a>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Payment Date</th>
                                <th>Ammount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT t.*, p.name AS patient_name, p.profile_img AS patient_img, p.patient_id,
                                    d.name AS doctor_name, d.profile_img AS doctor_img, d.doctor_id
                            FROM transaction t
                            JOIN patient p ON t.patient_id = p.patient_id
                            JOIN doctor d ON t.doctor_id = d.doctor_id
                            WHERE t.payment_status = 'Paid'
                            ORDER BY t.transaction_date DESC
                            LIMIT 5";
                    
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) { 
                                    while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td>
                                    <img src="../upload/patient/<?= htmlspecialchars($row['patient_img']); ?>"
                                        alt="Patient Image">
                                    <strong><?= htmlspecialchars($row['patient_name']); ?></strong>
                                    <span>Id: <?= htmlspecialchars($row['patient_id']); ?></span>
                                </td>
                                <td>
                                    <img src="../upload/doctor/<?= htmlspecialchars($row['doctor_img']); ?>"
                                        alt="Doctor Image">
                                    <strong><?= htmlspecialchars($row['doctor_name']); ?></strong>
                                    <span>Id: <?= htmlspecialchars($row['doctor_id']); ?></span>
                                </td>
                                <td><?= date("d/m/Y", strtotime($row['transaction_date'])); ?></td>
                                <td><i
                                        class="fa-solid fa-indian-rupee-sign"></i><?= htmlspecialchars($row['amount']); ?>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td colspan="4" style="text-align:center; font-weight:bold;">No transactions found</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="emergency">
                <div class="table-head">
                    <h3>Emergency Request</h3>
                    <a href="" id="view-btn">View All</a>
                </div>
                <?php
                $sql = "SELECT * FROM emergency_booking WHERE status = 'Pending' ORDER BY requested_at DESC LIMIT 1";

                $result = $conn->query($sql);
                 if ($result->num_rows > 0) { 
                     while ($row = $result->fetch_assoc()) { ?>
                <div class="emergency-box">
                    <div class="box-left">
                        <strong><?= htmlspecialchars($row['patient_name']); ?></strong>
                        <span id="type"><?= htmlspecialchars($row['emergency_type']); ?></span>
                        <small><?= date("d/m/Y", strtotime($row['requested_at'])); ?></small>
                    </div>
                    <div class="box-right">
                        <span id="status"><?= htmlspecialchars($row['status']); ?></span>
                    </div>
                </div>
                <?php } ?>
                <?php } else { ?>
                <div class="no-emergency" style="text-align:center; font-weight:bold; padding:10px;">No emergency
                    requests found</div>
                <?php } ?>
            </div>
        </section>
    </main>
    <?php include 'admin-footer.php'?>
</body>

</html>