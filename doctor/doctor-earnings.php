<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

$total_paid = 0.00;
$total_pending = 0.00;

// Get total paid
$paid_query = "SELECT SUM(amount) AS total_paid FROM transaction WHERE doctor_id = '$doctor_id' AND payment_status = 'Paid'";
$paid_result = mysqli_query($conn, $paid_query);
if ($paid_result) {
    $paid_row = mysqli_fetch_assoc($paid_result);
    $total_paid = $paid_row['total_paid'] ?? 0.00;
}

// Get total pending
$pending_query = "SELECT SUM(amount) AS total_pending FROM transaction WHERE doctor_id = '$doctor_id' AND payment_status = 'Pending'";
$pending_result = mysqli_query($conn, $pending_query);
if ($pending_result) {
    $pending_row = mysqli_fetch_assoc($pending_result);
    $total_pending = $pending_row['total_pending'] ?? 0.00;
}

$conditions = ["t.doctor_id = '$doctor_id'"];

// Search filter (doctor or clinic)
if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $conditions[] = "(p.name LIKE '%$search%' OR cl.clinic_name LIKE '%$search%')";
}

// Date range filter
if (!empty($_GET['from_date'])) {
    $from_date = mysqli_real_escape_string($conn, $_GET['from_date']);
    $conditions[] = "DATE(t.transaction_date) >= '$from_date'";
}

if (!empty($_GET['to_date'])) {
    $to_date = mysqli_real_escape_string($conn, $_GET['to_date']);
    $conditions[] = "DATE(t.transaction_date) <= '$to_date'";
}

// Payment status filter
if (!empty($_GET['status'])) {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $conditions[] = "t.payment_status = '$status'";
}

// Payment method filter
if (!empty($_GET['method'])) {
    $method = mysqli_real_escape_string($conn, $_GET['method']);
    $conditions[] = "t.payment_mode = '$method'";
}

$where_clause = implode(" AND ", $conditions);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Payment Management</title>
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

    /* first section----------------- */
    .first-sec {
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }

    .count-box {
        flex: 0 0 500px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.12);
        gap: 5px;
    }

    .box1 {
        background: linear-gradient(135deg, #dbeafe, #eff6ff);
    }

    .box2 {
        background: linear-gradient(135deg, #dcfce7, #f0fdf4);
    }

    /* second section-------------------------- */
    .second-sec {
        display: flex;
        flex-direction: column;
        padding: 10px;
        gap: 10px;
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
        gap: 10px;
        width: 100%;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-group select,
    input[type="date"] {
        padding: 10px;
        border: 1px solid #BFDBFE;
        border-radius: 5px;
        outline: none;
    }

    .filter-group label {
        font-size: 12px;
    }

    #search-btn,
    #filter-btn {
        background-color: #3B82F6;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
    }

    .security-note {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #4B5563;
    }

    /* third section------------------------- */
    .third-sec {
        margin: 20px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .table {
        overflow-y: auto;
        overflow-x: auto;
        max-height: 300px;
    }

    thead {
        background-color: #3B82F6;
        color: white;
        position: sticky;
        top: 0;
    }

    th,
    td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        white-space: nowrap;
    }

    td {
        font-size: 14px;
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

    tbody a {
        text-decoration: none;
        color: #3B82F6;
    }

    tbody img {
        height: 30px;
        width: 30px;
        border-radius: 50%;
        object-fit: fill;
    }
    </style>
</head>

<body>
    <?php include 'doctor-header.php';?>
    <main>
        <section class="first-sec">
            <div class="count-box box1">
                <p>Total Paid</p>
                <h2><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($total_paid, 2); ?></h2>
            </div>

            <div class="count-box box2">
                <p>Total Pending</p>
                <h2><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($total_pending, 2); ?></h2>
            </div>
        </section>

        <section class="second-sec">
            <form class="sf-part" method="GET">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar" placeholder="Search by Patient, Clinic....."
                        value="<?= $_GET['search'] ?? '' ?>">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <label>From</label>
                        <input type="date" name="from_date" value="<?= $_GET['from_date'] ?? '' ?>">
                    </div>
                    <div class="filter-group">
                        <label>To</label>
                        <input type="date" name="to_date" value="<?= $_GET['to_date'] ?? '' ?>">
                    </div>
                    <div class="filter-group">
                        <select name="status">
                            <option value="">All Payment Status</option>
                            <option value="Paid" <?= (($_GET['status'] ?? '') === 'Paid') ? 'selected' : '' ?>>Paid
                            </option>
                            <option value="Pending" <?= (($_GET['status'] ?? '') === 'Pending') ? 'selected' : '' ?>>
                                Pending</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="method">
                            <option value="">All Payment Method</option>
                            <option value="Online" <?= (($_GET['method'] ?? '') === 'Online') ? 'selected' : '' ?>>
                                Online</option>
                            <option value="Offline" <?= (($_GET['method'] ?? '') === 'Offline') ? 'selected' : '' ?>>
                                Offline</option>
                        </select>
                    </div>
                    <button type="submit" id="filter-btn">Apply Filter</button>
                </div>
            </form>

            <div class="security-note">
                <i class="fa-solid fa-lock"></i>
                <span>All transactions are secured and encrypted.</span>
            </div>

        </section>

        <section class="third-sec">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <td>Sl No</td>
                            <th>Patient</th>
                            <th>Clinic</th>
                            <th>Appointment Date</th>
                            <th>Payment Data</th>
                            <td>Amount</td>
                            <td>Method</td>
                            <th>Status</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT 
                            t.*, 
                            p.name AS patient_name, p.profile_img,
                            cl.clinic_name, 
                            a.appointment_date,
                            d.name AS doctor_name
                            FROM transaction t
                            JOIN appointment a ON t.appointment_id = a.appointment_id
                            JOIN patient p ON t.patient_id = p.patient_id
                            JOIN doctor_clinic cl ON a.clinic_id = cl.clinic_id
                            JOIN doctor d ON a.doctor_id = d.doctor_id
                            WHERE $where_clause
                            ORDER BY t.transaction_date DESC";          

                        $result = mysqli_query($conn, $query);
                        $sl = 1;
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $profile_img = !empty($row['profile_img']) ? $row['profile_img'] : 'user.jpg';
                                echo "<tr>
                                    <td>{$sl}</td>
                                    <td>
                                        <img src='../upload/patient/{$profile_img}' alt='Patient Image'>
                                        <h4>{$row['patient_name']}</h4>
                                    </td>
                                    <td>{$row['clinic_name']}</td>
                                    <td>" . date("M d, Y", strtotime($row['appointment_date'])) . "</td>
                                    <td>" . date("M d, Y, h:i A", strtotime($row['transaction_date'])) . "</td>
                                    <td><i class='fa-solid fa-indian-rupee-sign'></i> <span>{$row['amount']}</span></td>
                                    <td>{$row['payment_mode']}</td>
                                    <td><span id='back'>{$row['payment_status']}</span></td>
                                    <td><a href='download-invoice.php?id={$row['transaction_id']}'><i class='fa-solid fa-file-arrow-down'></i> Download</a></td>
                                </tr>";
                                $sl++;
                            }
                        } else {
                            echo "<tr><td colspan='9' style='text-align:center;'>No Payment Records Found</td></tr>";
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </section>
    </main>
    <?php include 'doctor-footer.php';?>
</body>

</html>