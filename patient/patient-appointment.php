<?php
session_start();
include '../connection.php'; // DB connection

// Check login
if (!isset($_SESSION['patient_id'])) {
    header("Location: patient-login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];
$conditions = ["a.patient_id = '$patient_id'"];

// Handle tab (Upcoming / Past)
if (isset($_GET['tab']) && $_GET['tab'] === 'past') {
    $conditions[] = "a.status <> 'Scheduled'";
} else {
    $conditions[] = "a.status = 'Scheduled'";
}

// Handle search
if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $conditions[] = "(d.name LIKE '%$search%' OR c.clinic_name LIKE '%$search%' OR c.city LIKE '%$search%' OR c.state LIKE '%$search%' OR c.area LIKE '%$search%')";
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

// Final query
$where_clause = implode(" AND ", $conditions);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Appointment</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
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

    /* first section-------------------- */
    .first-sec {
        display: flex;
        flex-direction: column;
        gap: 20px;
        align-items: flex-start;
    }

    .tabs {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .tab {
        padding: 10px 20px;
        border: 2px solid transparent;
        cursor: pointer;
        text-decoration: none;
        color: #6B7280;
        background-color: transparent;
        font-size: 16px;
    }

    .tab.active {
        border-bottom: 2px solid #007bff;
        color: #007bff;
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

    .filter-group select {
        padding: 10px;
        border: 1px solid #BFDBFE;
        border-radius: 5px;
        outline: none;
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

    /* second section---------------------- */
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

    td img {
        height: 30px;
        width: 30px;
        border-radius: 50%;
        object-fit: fill;
    }

    td h3 {
        font-size: 14px;
    }

    .status {
        padding: 5px;
        border-radius: 8px;
        color: #166534;
        background-color: #DCFCE7;
    }

    .confirmation {
        padding: 5px;
        border-radius: 8px;
        background-color: #FEF9C3;
        color: #854D0E;
    }

    .payment {
        padding: 5px;
        border-radius: 8px;
        background-color: #cff2f8;
        color: #0e4a85;
    }

    .action {
        text-decoration: none;
        color: #1B9AF5;
        margin-right: 5px;
        font-size: 16px;
    }

    .reschedule i {
        color: #EAB308;
    }

    .cancel i {
        color: #EF4444;
    }

    /* Responsive Design */
    /* @media (max-width: 1024px) {
            .main-content {
                margin: 90px 0 0 0; 
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            th, td {
                font-size: 14px;
                padding: 8px;
            }

            .action {
                padding: 6px 10px;
            }
        } */
    </style>
    <title>My Appointments</title>
</head>

<body>
    <!-- Include the header -->
    <?php include 'patient-header.php'; ?>
    <main>
        <!-- Main Content Container -->
        <section class="first-sec">
            <div class="tabs">
                <button type="button"
                    class="tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'upcoming') ? 'active' : ''; ?>"
                    onclick="setTab('upcoming')">Upcoming Appointments</button>
                <button type="button"
                    class="tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'past') ? 'active' : ''; ?>"
                    onclick="setTab('past')">Past Appointments</button>
            </div>

            <form method="GET" class="sf-part">
            <input type="hidden" name="tab" id="tab-input" value="<?php echo isset($_GET['tab']) ? $_GET['tab'] : 'upcoming'; ?>">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar"
                        placeholder="Search by Doctor, Clinic, Location....."
                        value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="Scheduled" <?php if(@$_GET['status'] == 'Scheduled') echo 'selected'; ?>>
                                Scheduled</option>
                            <option value="Completed" <?php if(@$_GET['status'] == 'Completed') echo 'selected'; ?>>
                                Completed</option>
                            <option value="Cancled" <?php if(@$_GET['status'] == 'Cancled') echo 'selected'; ?>>Cancled
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="confirmation">
                            <option value="">All Confirmation</option>
                            <option value="Pending" <?php if(@$_GET['confirmation'] == 'Pending') echo 'selected'; ?>>
                                Pending</option>
                            <option value="Approved" <?php if(@$_GET['confirmation'] == 'Approved') echo 'selected'; ?>>
                                Approved</option>
                            <option value="Rejected" <?php if(@$_GET['confirmation'] == 'Rejected') echo 'selected'; ?>>
                                Rejected</option>
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
                            <th>Date & Time</th>
                            <th>Clinic</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Confirmation</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        $query = "SELECT 
                        a.*, 
                        d.name AS doctor_name, 
                        d.profile_img, 
                        c.clinic_name, 
                        c.city, 
                        c.area, 
                        c.state, 
                        c.pincode
                        FROM appointment a
                        JOIN doctor d ON a.doctor_id = d.doctor_id
                        JOIN doctor_clinic c ON a.clinic_id = c.clinic_id
                        WHERE $where_clause
                        ORDER BY a.created_at ASC";

                        $result = mysqli_query($conn, $query);
                        $sl = 1;
                    if (mysqli_num_rows($result) > 0) {
                    while ($app = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$sl}</td>
                            <td>
                                <img src='../upload/doctor/{$app['profile_img']}' alt='doc'>
                                <h3>{$app['doctor_name']}</h3>
                            </td>
                            <td>" . date("M d, Y", strtotime($app['appointment_date'])) . "<br>" . date("h:i A", strtotime($app['appointment_time'])) . "</td>
                            <td>{$app['clinic_name']}</td>
                            <td>{$app['city']}<br>{$app['area']}<br>{$app['state']} - {$app['pincode']}</td>
                            <td><span class='status'>{$app['status']}</span></td>
                            <td><span class='confirmation'>{$app['doctor_confirmation']}</span></td>
                            <td><span class='payment'>{$app['payment_status']}</span></td>
                            <td>
                                <a href='view-appointment.php?id={$app['appointment_id']}' class='action view' title='View'><i class='fa-solid fa-eye'></i></a>
                                <a href='patient-rescheduleAppointmenet.php?id={$app['appointment_id']}' class='action reschedule' title='Reschedule'><i class='fa-solid fa-calendar-xmark'></i></a>
                                <a href='patient-cancelAppointment.php?id={$app['appointment_id']}' class='action cancel' title='Cancel' onclick='return confirm(\"Are you sure you want to cancel this appointment?\")'><i class='fa-solid fa-xmark'></i></a>
                            </td>
                        </tr>";
                        $sl++;
                    }
                } else {
                    echo "<tr><td colspan='9' style='text-align:center;'>No Appointments Found</td></tr>";
                }
                ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>
    <script>
    function setTab(tabName) {
        document.getElementById('tab-input').value = tabName;
        document.querySelector('form.sf-part').submit();
    }
    </script>

    <?php include 'patient-footer.php'; ?>
</body>

</html>