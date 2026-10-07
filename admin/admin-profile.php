<?php
session_start();
include '../connection.php';

// Assuming admin is logged in
$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header("Location: admin-login.php");
    exit();
}

// Default values
$company_name = $email = $phone = $address = "";
$logo_path = '../img/logo.jpg'; // fallback if no logo uploaded

// Fetch admin profile info
$sql = "SELECT * FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    $name = $admin['name'] ?? 'N/A';
    $email = $admin['email'] ?? 'N/A';
    $phone = $admin['phone'] ?? 'N/A';
    $address = $admin['address'] ?? 'N/A';
    $facebook = $admin['facebook_link'] ?? 'N/A';
    $instagram = $admin['instagram_link'] ?? 'N/A';
    $linkedin = $admin['linkedin_link'] ?? 'N/A';
    $youtube = $admin['youtube_link'] ?? 'N/A';
    if (!empty($admin['logo']) && file_exists('../upload/admin/' . $admin['logo'])) {
        $logo_path = '../upload/admin/' . $admin['logo'];
    }
}

// Doctor count
$doctor_count = 0;
$result = mysqli_query($conn, "SELECT COUNT(DISTINCT doctor_id) AS total FROM doctor");
if ($row = mysqli_fetch_assoc($result)) {
    $doctor_count = $row['total'];
}

// Patient count
$patient_count = 0;
$result = mysqli_query($conn, "SELECT COUNT(DISTINCT patient_id) AS total FROM patient");
if ($row = mysqli_fetch_assoc($result)) {
    $patient_count = $row['total'];
}

// Total income (only paid transactions)
$total_income = 0;
$result = mysqli_query($conn, "SELECT SUM(amount) AS total FROM transaction WHERE payment_status = 'Paid'");
if ($row = mysqli_fetch_assoc($result)) {
    $total_income = $row['total'] ?? 0;
}

// Completed appointments
$appointment_count = 0;
$result = mysqli_query($conn, "SELECT COUNT(appointment_id) AS total FROM appointment WHERE status = 'Completed'");
if ($row = mysqli_fetch_assoc($result)) {
    $appointment_count = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Profile</title>
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
        display: flex;
        flex-direction: row-reverse;
        gap: 25px;
    }



    .profile-sidebar {
        width: 300px;
        display: flex;
        flex-direction: column;
        gap: 25px;
        background-color: #fdfdfd;
    }

    .card {
        padding: 24px;
        border-bottom: 2px solid #d8f1f8;
    }

    .profile-pic {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto;
    }

    .center {
        text-align: center;
    }

    .name {
        font-weight: 600;
        font-size: 20px;
        margin-top: 12px;
    }

    .role {
        color: #6b7280;
        font-size: 14px;
    }

    .stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 24px;
    }

    .stat {
        background: #cde5fa;
        padding: 12px;
        border-radius: 8px;
    }

    .stat-title {
        font-size: 12px;
        margin-bottom: 5px;
    }

    .stat-value {
        font-weight: 600;
        font-size: 16px;
    }

    .card h3 {
        font-size: 18px;
        margin-bottom: 20px;
    }

    .social {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .social a {
        text-decoration: none;
        color: #3B82F6;
        font-size: 18px;
    }

    .social input {
        flex: 1;
        border: 1px solid #BFDBFE;
        color: #374151;
    }

    .delete {
        text-decoration: none;
        background-color: #f9dbdb;
        color: #dc2626;
        border-radius: 5px;
        padding: 5px 10px;
        margin: 0 auto;
    }

    .main {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .section {
        width: 900px;
        background: #fdfdfd;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        
    }

    .section h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    label {
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        display: block;
        margin-bottom: 6px;
    }

    input,
    textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #BFDBFE;
        border-radius: 4px;
        font-size: 14px;
        margin-bottom: 10px;
        color: #374151;
        outline-color: #3B82F6;
    }

    textarea {
        resize: vertical;
    }

    .full {
        grid-column: span 2;
    }

    .button {
        margin-top: 16px;
        background: #2563EB;
        color: #fff;
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        cursor: pointer;
        float: right;
    }


    .table {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 300px;
    }

    table {
        min-width: 800px;
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
    </style>
</head>

<body>

    <?php include 'admin-header.php'?>
    <main>
        <div class="profile-sidebar">
            <div class="card center">
                <img src="<?= $logo_path ?>" alt="Profile" class="profile-pic">
                <div class="name"><?= $name ?></div>
                <p>Admin Id: <?= $admin_id ?></p>
                <div class="role">Your Health our responsibility</div>
                <div class="stats">
                    <div class="stat">
                        <div class="stat-title">Doctors</div>
                        <div class="stat-value"><?= $doctor_count ?></div>
                    </div>
                    <div class="stat">
                        <div class="stat-title">Patients</div>
                        <div class="stat-value"><?= $patient_count ?></div>
                    </div>
                    <div class="stat">
                        <div class="stat-title">Transactions</div>
                        <div class="stat-value">
                            <i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($total_income) ?>
                        </div>
                    </div>
                    <div class="stat">
                        <div class="stat-title">Appointment</div>
                        <div class="stat-value"><?= $appointment_count ?></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>Social Media</h3>
                <form method="POST" enctype="multipart/form-data" action="admin-p2-submit.php">
                    <div class="social">
                        <a href=""><i class="fa-brands fa-linkedin"></i></a>
                        <input type="text" name="linkedin_link" value="<?= $linkedin ?>" />
                    </div>
                    <div class="social">
                        <a href=""><i class="fa-brands fa-instagram"></i></a>
                        <input type="text" name="instagram_link" value="<?= $instagram ?>" />
                    </div>
                    <div class="social">
                        <a href=""><i class="fa-brands fa-facebook"></i></a>
                        <input type="text" name="facebook_link" value="<?= $facebook ?>" />
                    </div>
                    <div class="social">
                        <a href=""><i class="fa-brands fa-youtube"></i></a>
                        <input type="text" name="youtube_link" value="<?= $youtube ?>" />
                    </div>
                    <button class="button" type="submit">Update Details</button>
                </form>
            </div>

            <a href="admin-profile-delete.php" class="delete">Delete Account</a>

        </div>

        <div class="main">

            <div class="section">
                <h3>Personal Information</h3>
                <form action="admin-p1-submit.php" method="POST" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div>
                            <label>Company Name</label>
                            <input type="text" name="name" value="<?= $name ?>" required />
                        </div>
                        <div>
                            <label>Email</label>
                            <input type="email" name="email" value="<?= $email ?>" required />
                        </div>
                        <div>
                            <label>Phone</label>
                            <input type="text" name="phone" value="<?= $phone ?>" />
                        </div>
                        <div>
                            <label>Profile Photo</label>
                            <input type="file" name="logo" accept="image/*" />
                        </div>
                        <div class="full">
                            <label>Address</label>
                            <textarea rows="2" name="address"><?= $address ?></textarea>
                        </div>
                    </div>
                    <button class="button" type="submit">Update Details</button>
                </form>
            </div>

            <div class="section">
                <h3>Security Settings</h3>
                <label>Current Password</label>
                <input type="password" placeholder="Enter current password" />
                <label>New Password</label>
                <input type="password" placeholder="Enter new password" />
                <label>Confirm Password</label>
                <input type="password" placeholder="Confirm new password" />
                <button class="button">Update Password</button>
            </div>

            <div class="section">
                <h3>Recent Activity</h3>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Sl no</th>
                                <th>Login</th>
                                <th>Logout</th>
                                <th>Ip address</th>
                                <th>Device</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "SELECT * 
                                        FROM log_info 
                                        WHERE admin_id = $admin_id 
                                        AND log_type = 'admin' 
                                        AND status = 'Success' 
                                        ORDER BY login_time DESC";
                                
                                $result = mysqli_query($conn, $query);
                                $sl = 1;

                        
                                if (!empty($result) && mysqli_num_rows($result) > 0): 
                                while ($log = mysqli_fetch_assoc($result)): 
                                ?>
                            <tr>
                                <td><?= $sl++ ?></td>
                                <td><?= date('M d, Y h:i A', strtotime($log['login_time'])) ?></td>
                                <td>
                                    <?= $log['logout_time'] ? date('M d, Y h:i A', strtotime($log['logout_time'])) : '<span style="color: green;">Active</span>' ?>
                                </td>
                                <td><?= htmlspecialchars($log['ip_address']) ?></td>
                                <td><?= htmlspecialchars($log['device_info']) ?></td>
                                <td><?= htmlspecialchars($log['location']) ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6">No login history found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php include 'admin-footer.php'?>
</body>

</html>