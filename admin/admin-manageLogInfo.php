<?php
include '../connection.php';
session_start();

// Verify user authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.html");
    exit();
}

$admin_id = $_SESSION['admin_id'];
// Collect inputs
$search = trim($_GET['search'] ?? '');
$type   = $_GET['type'] ?? 'all';
$order  = $_GET['order'] ?? 'latest';

// Build base query
$conditions = [];

// Add search condition
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $conditions[] = "(log_info.admin_id LIKE '%$search%' 
                      OR log_info.doctor_id LIKE '%$search%' 
                      OR log_info.patient_id LIKE '%$search%' 
                      OR admin.name LIKE '%$search%' 
                      OR doctor.name LIKE '%$search%' 
                      OR patient.name LIKE '%$search%')";
}

// Add type condition
if ($type !== 'all') {
    $conditions[] = "log_info.log_type = '$type'";
}

// Construct the WHERE clause
$where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Add sorting condition
$order_by = ($order === 'oldest') ? 'ORDER BY login_time ASC' : 'ORDER BY login_time DESC';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Info</title>
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

    .filter-group select {
        padding: 10px;
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
                <h2>Login Information</h2>
            </div>
            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar"
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Search by id, name.....">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <select name="type">
                            <option value="all"
                                <?= (isset($_GET['type']) && $_GET['type'] === 'all') ? 'selected' : '' ?>>All Type
                            </option>
                            <option value="admin"
                                <?= (isset($_GET['type']) && $_GET['type'] === 'admin') ? 'selected' : '' ?>>Admin
                            </option>
                            <option value="doctor"
                                <?= (isset($_GET['type']) && $_GET['type'] === 'doctor') ? 'selected' : '' ?>>Doctor
                            </option>
                            <option value="patient"
                                <?= (isset($_GET['type']) && $_GET['type'] === 'patient') ? 'selected' : '' ?>>Patient
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="order">
                            <option value="latest"
                                <?= (isset($_GET['order']) && $_GET['order'] === 'latest') ? 'selected' : '' ?>>Latest
                                First</option>
                            <option value="oldest"
                                <?= (isset($_GET['order']) && $_GET['order'] === 'oldest') ? 'selected' : '' ?>>Oldest
                                First</option>
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
                            <th>User Id</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Login</th>
                            <th>Logout</th>
                            <th>Ip address</th>
                            <th>Device</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                            $query = "SELECT 
                            log_info.*, 
                            CASE 
                                WHEN log_type = 'admin' THEN admin.name
                                WHEN log_type = 'doctor' THEN doctor.name
                                WHEN log_type = 'patient' THEN patient.name
                                ELSE NULL
                            END AS user_name,
                            CASE 
                                WHEN log_type = 'admin' THEN admin.logo
                                WHEN log_type = 'doctor' THEN doctor.profile_img
                                WHEN log_type = 'patient' THEN patient.profile_img
                                ELSE NULL
                            END AS user_photo
                        FROM log_info
                        LEFT JOIN admin ON log_info.admin_id = admin.admin_id
                        LEFT JOIN doctor ON log_info.doctor_id = doctor.doctor_id
                        LEFT JOIN patient ON log_info.patient_id = patient.patient_id
                        $where_clause
                        $order_by";

                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $sl = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $userId     = $row['admin_id'] ?? $row['doctor_id'] ?? $row['patient_id'] ?? 'N/A';
                                $userName   = htmlspecialchars($row['user_name'] ?? 'N/A');
                                $userType   = ucfirst($row['log_type']);
                                $loginTime  = date('d M, Y, h:i A', strtotime($row['login_time']));
                                $logoutTime = !empty($row['logout_time']) ? date('d M, Y, h:i A', strtotime($row['logout_time'])) : '-';
                                $ip         = htmlspecialchars($row['ip_address'] ?? 'N/A');
                                $device     = htmlspecialchars($row['device_info'] ?? 'N/A');
                                $location   = htmlspecialchars($row['location'] ?? 'N/A');

                                // Determine folder path dynamically
                                $folderPath = match (strtolower($row['log_type'] ?? '')) {
                                    'admin' => '../upload/admin/',
                                    'doctor' => '../upload/doctor/',
                                    'patient' => '../upload/patient/',
                                    default => '../upload/unknown/',
                                };
                                $userPhoto = !empty($row['user_photo']) ? $folderPath . $row['user_photo'] : '../img/default-user.jpg';

                                echo "<tr>
                                        <td>{$sl}</td>
                                        <td>Id: {$userId}</td>
                                        <td><img src='{$userPhoto}' alt='img'><strong>{$userName}</strong></td>
                                        <td>{$userType}</td>
                                        <td>{$loginTime}</td>
                                        <td>{$logoutTime}</td>
                                        <td>{$ip}</td>
                                        <td>{$device}</td>
                                        <td>{$location}</td>
                                    </tr>";
                                $sl++;
                            }
                        } else {
                            echo "<tr><td colspan='9'>No login info found.</td></tr>";
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