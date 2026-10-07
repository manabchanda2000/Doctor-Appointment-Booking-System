<?php
include '../connection.php';

$defaultLogo = "../img/logo.jpg";
$logoPath = $defaultLogo;

// ✅ Get logo from admin table (only 1 active logo assumed)
$logoQuery = mysqli_query($conn, "SELECT logo FROM admin WHERE admin_id = 1 LIMIT 1");
if ($logoQuery && mysqli_num_rows($logoQuery) > 0) {
    $logoData = mysqli_fetch_assoc($logoQuery);
    if (!empty($logoData['logo']) && file_exists('../upload/admin/' . $logoData['logo'])) {
        $logoPath = '../upload/admin/' . $logoData['logo'];
    }
}

// ✅ Get last login location for current patient
$location = "Unknown";
if (isset($_SESSION['doctor_id'])) {
    $pid = $_SESSION['doctor_id'];
    $locQuery = mysqli_query($conn, "SELECT location FROM log_info WHERE doctor_id = $pid ORDER BY login_time DESC LIMIT 1");
    if ($locQuery && mysqli_num_rows($locQuery) > 0) {
        $locData = mysqli_fetch_assoc($locQuery);
        $location = $locData['location'] ?? "Unknown";
    }
}

//patient profile
$doctor_id = $_SESSION['doctor_id'] ?? null;

$doctor_img = '../img/user.jpg'; // default image
$doctor_name = 'Guest';
$doctor_id_display = '---';

if ($doctor_id) {
    $sql = "SELECT name, profile_img FROM doctor WHERE doctor_id = '$doctor_id'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $doctor_name = $row['name'];
        $doctor_id_display = $doctor_id;

        // If profile image exists, use it
        if (!empty($row['profile_img']) && file_exists('../upload/doctor/' . $row['profile_img'])) {
            $doctor_img = '../upload/doctor/' . $row['profile_img'];
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

    .header-left img {
        height: 40px;
        width: 40px;
        border-radius: 50%;
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
    aside {
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
    .aside a .current {
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
        border: 2px solid #16c2d5;
        object-fit: cover;
    }

    .profile h3 {
        margin-bottom: 5px;
        font-size: 18px;
        color: #527c88;
    }

    .profile p {
        font-size: 14px;
        color: #2e4450;
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
            <img src="<?= $logoPath ?>" alt="logo">
            <h2>
                <?php
            $pageName = basename($_SERVER['PHP_SELF'], '.php');
            $pageName = str_replace('-', ' ', $pageName);
            $pageName = ucwords($pageName);
            echo $pageName;
            ?>
            </h2>
        </div>
        <div class="header-right">
            <div id='location-box'><i class="fa-solid fa-location-dot"></i><span> <?= $location ?> </span></div>
            <i class="fa-solid fa-bell"></i>
            <a href="doctor-logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </header>

    <aside>
        <a href="../doctor/doctor-profile.php" class="profile">
            <span>
                <img src="<?= $doctor_img ?>" alt="Profile Picture">
            </span>
            <span>
                <h3><?= htmlspecialchars($doctor_name) ?></h3>
                <p>Doctor ID: <?= htmlspecialchars($doctor_id_display) ?></p>
            </span>
        </a>

        <ul class="menu">
            <li><a href="doctor-dashboard.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'doctor-dashboard.php' ? 'current' : '' ?>"><i
                        class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="doctor-patients-record.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'doctor-patients-record.php' ? 'current' : '' ?>"><i
                        class="fa-solid fa-user-doctor"></i> Patients Record</a></li>
            <li><a href="doctor-appointment.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'doctor-appointment.php' ? 'current' : '' ?>"><i
                        class="fa-solid fa-calendar-check"></i>My Appointments</a></li>
            <li><a href="doctor-prescription.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'doctor-prescription.php' ? 'current' : '' ?>"><i
                        class="fa-solid fa-file-prescription"></i> Prescription</a></li>
            <li><a href="doctor-clinic.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'doctor-clinic.php' ? 'current' : '' ?>"><i
                        class="fa-solid fa-hospital"></i> Clinics</a></li>
            <li><a href="doctor-reviews.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'doctor-reviews.php' ? 'current' : '' ?>"><i
                        class="fa-solid fa-star"></i> Reviews</a></li>
            <li><a href="doctor-emergency.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'doctor-emergency.php' ? 'current' : '' ?>"><i
                        class="fa-solid fa-kit-medical"></i> Emergency Booking</a></li>
            <li><a href="doctor-announcements.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'doctor-announcements.php' ? 'current' : '' ?>"><i
                        class="fa-solid fa-bullhorn"></i> Announcements </a></li>
            <li><a href="doctor-healthtips.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'doctor-healthtips.php' ? 'current' : '' ?>"><i
                        class="fa-solid fa-user-nurse"></i> Health Tips</a></li>
            <li><a href="doctor-qna.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'doctor-qna.php' ? 'current' : '' ?>"><i
                        class="fa-solid fa-circle-question"></i> Q&A </a></li>
            <li><a href="doctor-earnings.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'doctor-earnings.php' ? 'current' : '' ?>"><i
                        class="fa-solid fa-money-bill"></i> Earnings </a></li>
        </ul>
    </aside>
</body>

</html>