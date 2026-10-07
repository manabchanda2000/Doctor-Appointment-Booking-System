<?php
include '../connection.php';
session_start();

// Verify user authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.html");
    exit();
}

$admin_id = $_SESSION['admin_id'];

$conditions = [];

// Handle search
if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
    $conditions[] = "(d.name LIKE '%$search%' 
                      OR p.name LIKE '%$search%' 
                      OR dc.clinic_name LIKE '%$search%')";
}

// Handle status filter
if (!empty($_GET['status'])) {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $conditions[] = "a.status = '$status'";
}

// Handle confirmation filter
if (!empty($_GET['confirmation'])) {
    $confirmation = mysqli_real_escape_string($conn, $_GET['confirmation']);
    $conditions[] = "a.doctor_confirmation = '$confirmation'";
}

// Handle payment status filter
if (!empty($_GET['payment_status'])) {
    $payment_status = mysqli_real_escape_string($conn, $_GET['payment_status']);
    $conditions[] = "a.payment_status = '$payment_status'";
}

// Handle payment mode filter
if (!empty($_GET['payment_mode'])) {
    $payment_mode = mysqli_real_escape_string($conn, $_GET['payment_mode']);
    $conditions[] = "a.payment_mode = '$payment_mode'";
}

// Handle appointment mode filter
if (!empty($_GET['appointment_mode'])) {
    $appointment_mode = mysqli_real_escape_string($conn, $_GET['appointment_mode']);
    $conditions[] = "a.mode_of_appointment = '$appointment_mode'";
}

// Handle date range filters
if (!empty($_GET['date_from'])) {
    $date_from = mysqli_real_escape_string($conn, $_GET['date_from']);
    $conditions[] = "DATE(a.appointment_date) >= '$date_from'";
}
if (!empty($_GET['date_to'])) {
    $date_to = mysqli_real_escape_string($conn, $_GET['date_to']);
    $conditions[] = "DATE(a.appointment_date) <= '$date_to'";
}

// Build WHERE clause
$where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Handle sorting order
$order = ($_GET['sort'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointment</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    main {
        background: linear-gradient(135deg, #f2f8fc, #f0f8ff);
        padding: 80px 20px 20px 280px;
    }

    /* first section----------------------- */
    .first-sec {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 10px;
        gap: 20px;
    }

    .heading-part {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .sf-part {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
        flex-wrap: wrap;
    }

    .search-bar {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #fdfdfd;
        padding: 10px;
        border-radius: 5px;
        font-size: 14px;
        border: 1px solid #BFDBFE;
    }

    .search-bar #search-bar {
        flex: 1;
        padding: 5px;
        outline: none;
        border: none;
        background-color: transparent;
    }

    .filter {
        display: flex;
        align-items: center;
        /* justify-content: space-between; */
        gap: 20px;
        width: 100%;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-group label {
        font-size: 12px;
    }

    .filter-group select,
    input[type="date"] {
        padding: 10px;
        outline: none;
        border: 1px solid #BFDBFE;
        border-radius: 5px;
    }


    #search-btn,
    #filter-btn,
    #add-btn {
        text-decoration: none;
        background-color: #2563EB;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
        font-size: 14px;
    }

    /* second section------------------- */
    .second-sec {
        margin: 20px 0;
    }

    .table {
        overflow-y: auto;
        overflow-x: auto;
        max-height: 600px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background-color: #3B82F6;
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
        background-color: #e1eefc;
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
    </style>
</head>

<body>
    <?php include 'admin-header.php'?>
    <main>
        <section class="first-sec">
            <div class="heading-part">
                <h2>Manage Appointment</h2>
                <!-- <a href="" id="add-btn">Add Patients</a> -->
            </div>
            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar"
                        placeholder="Search by Doctor, Patient, Clinic....."
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <select name="status">
                            <option value=""
                                <?= (isset($_GET['status']) && $_GET['status'] === '') ? 'selected' : '' ?>>All Status
                            </option>
                            <option value="Scheduled"
                                <?= (isset($_GET['status']) && $_GET['status'] === 'Scheduled') ? 'selected' : '' ?>>
                                Scheduled</option>
                            <option value="Completed"
                                <?= (isset($_GET['status']) && $_GET['status'] === 'Completed') ? 'selected' : '' ?>>
                                Completed</option>
                            <option value="Cancelled"
                                <?= (isset($_GET['status']) && $_GET['status'] === 'Cancelled') ? 'selected' : '' ?>>
                                Cancelled</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="confirmation">
                            <option value=""
                                <?= (isset($_GET['confirmation']) && $_GET['confirmation'] === '') ? 'selected' : '' ?>>
                                All Confirmation</option>
                            <option value="Pending"
                                <?= (isset($_GET['confirmation']) && $_GET['confirmation'] === 'Pending') ? 'selected' : '' ?>>
                                Pending</option>
                            <option value="Approved"
                                <?= (isset($_GET['confirmation']) && $_GET['confirmation'] === 'Approved') ? 'selected' : '' ?>>
                                Approved</option>
                            <option value="Rejected"
                                <?= (isset($_GET['confirmation']) && $_GET['confirmation'] === 'Rejected') ? 'selected' : '' ?>>
                                Rejected</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="payment_status">
                            <option value=""
                                <?= (isset($_GET['payment_status']) && $_GET['payment_status'] === '') ? 'selected' : '' ?>>
                                All Payment Status</option>
                            <option value="Paid"
                                <?= (isset($_GET['payment_status']) && $_GET['payment_status'] === 'Paid') ? 'selected' : '' ?>>
                                Paid</option>
                            <option value="Pending"
                                <?= (isset($_GET['payment_status']) && $_GET['payment_status'] === 'Pending') ? 'selected' : '' ?>>
                                Pending</option>
                            <option value="Refunded"
                                <?= (isset($_GET['payment_status']) && $_GET['payment_status'] === 'Refunded') ? 'selected' : '' ?>>
                                Refunded</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="payment_mode">
                            <option value=""
                                <?= (isset($_GET['payment_mode']) && $_GET['payment_mode'] === '') ? 'selected' : '' ?>>
                                All Payment Mode</option>
                            <option value="Online"
                                <?= (isset($_GET['payment_mode']) && $_GET['payment_mode'] === 'Online') ? 'selected' : '' ?>>
                                Online</option>
                            <option value="Offline"
                                <?= (isset($_GET['payment_mode']) && $_GET['payment_mode'] === 'Offline') ? 'selected' : '' ?>>
                                Offline</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="appointment_mode">
                            <option value=""
                                <?= (isset($_GET['appointment_mode']) && $_GET['appointment_mode'] === '') ? 'selected' : '' ?>>
                                All Appointment Mode</option>
                            <option value="Physical"
                                <?= (isset($_GET['appointment_mode']) && $_GET['appointment_mode'] === 'Physical') ? 'selected' : '' ?>>
                                Physical</option>
                            <option value="Video Call"
                                <?= (isset($_GET['appointment_mode']) && $_GET['appointment_mode'] === 'Video Call') ? 'selected' : '' ?>>
                                Video Call</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="date_from">From</label>
                        <input type="date" name="date_from" id="date_from"
                            value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
                    </div>
                    <div class="filter-group">
                        <label for="date_to">To</label>
                        <input type="date" name="date_to" id="date_to"
                            value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
                    </div>
                    <div class="filter-group">
                        <select name="sort">
                            <option value="DESC"
                                <?= (isset($_GET['sort']) && $_GET['sort'] === 'DESC') ? 'selected' : '' ?>>Latest First
                            </option>
                            <option value="ASC"
                                <?= (isset($_GET['sort']) && $_GET['sort'] === 'ASC') ? 'selected' : '' ?>>Oldest First
                            </option>
                        </select>
                    </div>
                    <button type="submit" id="filter-btn">Apply Filter</button>
                </div>
            </form>

        </section>

        <section class="second-sec">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Sl no</th>
                            <th>Doctor</th>
                            <th>Patient</th>
                            <th>Clinic</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Status</th>
                            <th>Confirmation</th>
                            <th>Appointment Mode</th>
                            <th>Payment Status</th>
                            <th>Payment Mode</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                      $query = "SELECT 
                      a.*, 
                      d.name AS doctor_name, d.profile_img AS doctor_img,
                      p.name AS patient_name, p.profile_img AS patient_img,
                      dc.clinic_name
                      FROM appointment a
                      JOIN doctor d ON a.doctor_id = d.doctor_id
                      JOIN patient p ON a.patient_id = p.patient_id
                      JOIN doctor_clinic dc ON a.clinic_id = dc.clinic_id
                      $where_clause
                      ORDER BY a.appointment_date $order";

                      $result = mysqli_query($conn, $query);
                      $serial = 1;

                      if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                            <td>{$serial}</td>
                            <td>
                                <img src='../upload/patient/{$row['patient_img']}' alt='patient'>
                                <strong>{$row['patient_name']}</strong>
                                <span>Id: {$row['patient_id']}</span>
                            </td>
                            <td>
                                <img src='../upload/doctor/{$row['doctor_img']}' alt='doctor'>
                                <strong>{$row['doctor_name']}</strong>
                                <span>Id: {$row['doctor_id']}</span>
                            </td>
                            <td>
                                <strong>{$row['clinic_name']}</strong>
                                <span>Id: {$row['clinic_id']}</span>
                            </td>
                            <td>" . date("d M Y", strtotime($row['appointment_date'])) . "</td>
                            <td>" . date("g:i A", strtotime($row['appointment_time'])) . "</td>
                            <td>{$row['status']}</td>
                            <td>{$row['doctor_confirmation']}</td>
                            <td>{$row['mode_of_appointment']}</td>
                            <td>{$row['payment_status']}</td>
                            <td>{$row['payment_mode']}</td>
                            <td>" . date('d/m/Y h:i A', strtotime($row['created_at'])) . "</td>
                            <td>
                                <a href='../patient/view-appointment.php?id={$row['appointment_id']}'><i class='fa-solid fa-eye' title='View' id='view' style='color: #2563eb;'></i> </a>
                            </td>
                        </tr>";
                        $serial++;
                    }
                } else {
                    echo "<tr><td colspan='13'>No records found.</td></tr>";
                }
                ?>


                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <?php include 'admin-footer.php'?>
</body>

</html>