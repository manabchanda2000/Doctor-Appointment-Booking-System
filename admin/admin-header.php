<?php
include '../connection.php';

$location = "Unknown";
if (isset($_SESSION['admin_id'])) {
    $pid = $_SESSION['admin_id'];
    $locQuery = mysqli_query($conn, "SELECT location FROM log_info WHERE admin_id = $pid ORDER BY login_time DESC LIMIT 1");
    if ($locQuery && mysqli_num_rows($locQuery) > 0) {
        $locData = mysqli_fetch_assoc($locQuery);
        $location = $locData['location'] ?? "Unknown";
    }
}

$admin_id = $_SESSION['admin_id'] ?? null;

$admin_img = '../img/logo.jpg'; // default image
$admin_name = 'Guest';

if ($admin_id) {
    $sql = "SELECT * FROM admin WHERE admin_id = '$admin_id'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $admin_name = $row['name'];

        // If profile image exists, use it
        if (!empty($row['logo']) && file_exists('../upload/admin/' . $row['logo'])) {
            $admin_img = '../upload/admin/' . $row['logo'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar with Navbar</title>
    <!-- Google Font: Inter -->
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

    /* header */
    header {
        position: fixed;
        top: 0;
        left: 260px;
        background: linear-gradient(135deg, #EFF6FF 0%, #EEF2FF 50%, #FAF5FF 100%);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        color: #2e4450;
        border-bottom: 1px solid #d1d2d5;
        z-index: 5;
        width: calc(100% - 260px);
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    #location-box {
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 14px;
        border: 1px solid #527c88;
    }

    .header-right i {
        font-size: 20px;
        cursor: pointer;
        color: #16c2d5;
        /* Cyan */
    }

    .header-right a {
        text-decoration: none;
        background-color: #EF4444;
        color: white;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        cursor: pointer;
        transition: 0.3s;
        border-radius: 5px;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 5px;
        /* Prevents text from wrapping */
    }

    .header-right a i {
        color: white;
    }

    .header-right a:hover {
        background-color: #c54040;

    }

    /* Sidebar styling */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 260px;
        height: 100vh;
        background: linear-gradient(135deg, #EFF6FF 0%, #EEF2FF 50%, #FAF5FF 100%);
        color: #2e4450;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        border-right: 1px solid #E5E7EB;
        overflow-y: auto;
        z-index: 10;
    }

    /* Active Page Styling */
    .sidebar a .current {
        background: #16c2d5;
        color: #fff;
        font-weight: bold;
    }

    .profile {
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #d5dded;
        width: 100%;
        padding-bottom: 10px;
        gap: 10px;
    }

    .profile img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .profile h3 {
        margin-bottom: 5px;
        font-size: 18px;
        color: #527c88;
    }

    .menu {
        list-style: none;
        padding: 10px 0;
        width: 100%;
    }

    .menu li {
        width: 100%;
    }

    .menu a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 20px;
        text-decoration: none;
        color: #2e4450;
        border-radius: 20px;
        font-size: 14px;
        margin-bottom: 5px;
        transition: 0.3s;
    }

    .menu a:hover {
        background-color: #89dee2;
    }

    .menu .active {
        background-color: #16c2d5;
        /* Cyan */
        color: #10217d;
        /* Dark blue */
    }

    /* Active Page Styling */
    .menu a.current {
        background: #16c2d5;
        color: white;
        font-weight: bold;
    }

    /* Responsive styling */
    @media (max-width: 1024px) {
        .sidebar {
            width: 220px;
        }

        .navbar {
            left: 220px;
            width: calc(100% - 220px);
        }

        .main-content {
            margin-left: 220px;
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }

        .navbar {
            left: 200px;
            width: calc(100% - 200px);
        }

        .main-content {
            margin-left: 200px;
        }
    }

    @media (max-width: 600px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .navbar {
            left: 0;
            width: 100%;
        }

        .main-content {
            margin-left: 0;
        }
    }
    </style>
</head>

<body>
    <header>
        <div class="header-left">
        
        </div>
        <div class="header-right">
            <div id='location-box'><i class="fa-solid fa-location-dot"></i><span> <?= $location ?> </span></div>
            <i class="fa-solid fa-bell"></i>
            <a href="admin-logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </header>

    <aside class="sidebar">
        <a href="admin-profile.php" class="profile">
            <span><img src="<?= $admin_img ?>" alt="logo"></span>
            <span>
                <h3><?= htmlspecialchars($admin_name) ?></h3>
            </span>
        </a>

        <ul class="menu">
            <li><a href="admin-dashboard.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-dashboard.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-house"></i> Dashboard</a></li>

            <li><a href="admin-manageLogInfo.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-manageLogInfo.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-key"></i> Login Info</a></li>

            <li><a href="admin-manageDoctor.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-manageDoctor.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-user-doctor"></i> Doctors</a></li>

            <li><a href="admin-manageDocReq.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-manageDocReq.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-user-plus"></i> Doctor Request</a></li>

            <li><a href="admin-manageClinic.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-manageClinic.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-hospital-user"></i> Clinics</a></li>

            <li><a href="admin-managePatient.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-managePatient.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-users"></i> Patients</a></li>

            <li><a href="admin-manageAppointment.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-manageAppointment.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-calendar-check"></i> Appointments</a></li>

            <li><a href="admin-manageEmergency.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-manageEmergency.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-triangle-exclamation"></i> Emergency Booking</a></li>

            <li><a href="admin-manageReview.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-manageReview.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-star"></i> Reviews</a></li>

            <li><a href="admin-manageAnnouncement.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-manageAnnouncement.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-bullhorn"></i> Announcements</a></li>

            <li><a href="admin-manageHealthTips.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-manageHealthTips.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-heart-pulse"></i> Health Tips</a></li>

            <li><a href="admin-manageQNA.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-manageQNA.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-circle-question"></i> Q&A</a></li>

            <li><a href="admin-managePayment.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-managePayment.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-money-bill-wave"></i> Transactions</a></li>

            <li><a href="admin-manageQuery.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-manageQuery.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-envelope-open-text"></i> Query</a></li>

            <li><a href="admin-manageTeam.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin-manageTeam.php' ? 'current' : '' ?>">
                    <i class="fa-solid fa-people-group"></i> Our Team</a></li>
        </ul>


    </aside>
    

</body>
</html>
