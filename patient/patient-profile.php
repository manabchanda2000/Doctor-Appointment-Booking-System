<?php
session_start();
// If user is not logged in, redirect to login
if (!isset($_SESSION['patient_id'])) {
    header("Location: patient-login.html");
    exit();
}
include '../connection.php';
$patient_id=$_SESSION['patient_id']??0;

// Fetch patient data safely
$stmt = $conn->prepare("SELECT * FROM patient WHERE patient_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

// Default image if none
$default_img = "../img/user.jpg";
$img = !empty($patient['profile_img']) ? '../upload/patient/' . $patient['profile_img'] : $default_img;
//calculate age
function calculateAge($dob) {
    if (!$dob) return null;

    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    return $age;
}
$age = !empty($patient['dob']) ? calculateAge($patient['dob']) : null;


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile</title>
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

    .first-sec {
        padding: 20px;
        background-color: #fdfdfd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        display: flex;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
    }

    .first-sec .img {
        height: fit-content;
    }

    .first-sec img {
        height: 100px;
        width: 100px;
        border-radius: 50%;
        object-fit: fill;
    }

    .first-sec #camera-back {
        position: relative;
        top: -38px;
        left: 78px;
        color: white;
        background-color: #1B9AF5;
        height: 35px;
        width: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.13);
    }

    .basic-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }

    .basic-details h3 {
        font-size: 20px;
    }

    .basic-details p {
        font-size: 14px;
        color: #4B5563;
    }

    .basic-details i {
        margin-right: 5px;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .action-btn a {
        text-decoration: none;
        padding: 8px 12px;
        border-radius: 5px;
    }

    .edit {
        background-color: #1B9AF5;
        color: white;
    }

    .download {
        background-color: #16A34A;
        color: white;
    }

    .password {
        background-color: #E5E7EB;
        color: #374151;
    }


    /* third section---------------------- */
    .third-sec {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .data-box {
        flex: 0 0 380px;
        padding: 20px;
        background-color: #fdfdfd;
        box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.13);
        border-radius: 8px;
    }

    .data-box h3 {
        font-size: 20px;
        margin-bottom: 20px;
    }

    .box-data {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .box-data div {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .box-data p {
        font-size: 14px;
        color: #4B5563;
    }

    .box-data h4 {
        font-size: 15px;
    }

    /* forth section-------------------- */
    .forth-sec {
        padding: 15px;
        background-color: #fdfdfd;
        border-radius: 8px;
        box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.15);
        margin: 20px 0;
    }

    .forth-sec h3 {
        font-size: 20px;
        margin-bottom: 20px;
    }

    .table {
        overflow-y: auto;
        overflow-x: auto;
        max-height: 300px;
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

    /* fifth section------------------------ */
    .fifth-sec {
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        background-color: #FEF2F2;
        border: 1px solid #FECACA;
        border-radius: 10px;
    }

    .fifth-sec h3 {
        color: #B91C1C;
        font-size: 20px;
    }

    .fifth-sec p {
        color: #DC2626;
        font-size: 14px;
    }

    .fifth-sec button {
        background-color: #DC2626;
        color: white;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        transition: all .3s ease;
        cursor: pointer;
    }

    .fifth-sec button:hover {
        background-color: #B91C1C;
    }
    </style>
</head>

<body>
    <?php include 'patient-header.php';?>
    <main>
        <section class="first-sec">
            <div class="img">
                <img src="<?= $img ?>" alt="patient">
            </div>
            <div class="basic-details">
                <h3><?= !empty($patient['name']) ? htmlspecialchars($patient['name']) : 'N/A' ?></h3>
                <p>Patient ID: <?= !empty($patient_id) ? $patient_id : 'N/A' ?></p>
                <p><i class="far fa-envelope"></i>
                    <?= !empty($patient['email']) ? htmlspecialchars($patient['email']) : 'N/A' ?></p>
                <p><i class="fas fa-phone"></i>
                    <?= !empty($patient['phone']) ? htmlspecialchars($patient['phone']) : 'N/A' ?></p>
            </div>
            <div class="action-btn">
                <a href="patient-editProfile.php?id=<?= $patient_id ?>" class="edit"><i class="fas fa-edit"></i> Edit
                    Profile</a>
                <a href="download.php?id=<?= $patient_id ?>" class="download"><i class="fas fa-download"></i>
                    Download</a>
                <a href="change-password.php?id=<?= $patient_id ?>" class="password"><i class="fas fa-key"></i> Change
                    Password</a>
            </div>
        </section>

        <section class="third-sec">
            <div class="data-box">
                <h3>Personal Information</h3>
                <div class="box-data">
                    <div>
                        <p>Date of Birth</p>
                        <h4><?= !empty($patient['dob']) ? date("d M, Y", strtotime($patient['dob'])) : 'N/A' ?></h4>
                    </div>
                    <div>
                        <p>Gender/Age</p>
                        <h4>
                            <?= !empty($patient['gender']) ? htmlspecialchars($patient['gender']) : 'N/A' ?> /
                            <?= $age !== null ? $age : 'N/A' ?>
                        </h4>
                    </div>
                    <div>
                        <p>Blood Group</p>
                        <h4><?= !empty($patient['blood_group']) ? htmlspecialchars($patient['blood_group']) : 'N/A' ?>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="data-box">
                <h3>Contact Details</h3>
                <div class="box-data">
                    <div>
                        <p>Address</p>
                        <h4><?= !empty($patient['area']) ? htmlspecialchars($patient['area']) : 'N/A' ?></h4>
                    </div>
                    <div>
                        <p>City, State</p>
                        <h4>
                            <?= !empty($patient['city']) ? htmlspecialchars($patient['city']) : 'N/A' ?>
                            <?= !empty($patient['state']) ? htmlspecialchars($patient['state']) : 'N/A' ?>
                            <?= !empty($patient['pincode']) ? htmlspecialchars($patient['pincode']) : 'N/A' ?>
                        </h4>
                    </div>
                    <div>
                        <p>Emergency Contact</p>
                        <h4><?= !empty($patient['emergency_contact']) ? htmlspecialchars($patient['emergency_contact']) : 'N/A' ?>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="data-box">
                <h3>Account Status</h3>
                <div class="box-data">
                    <div>
                        <p>Status</p>
                        <h4><?= !empty($patient['availability']) ? htmlspecialchars($patient['availability']) : 'N/A' ?>
                        </h4>
                    </div>
                    <div>
                        <p>Member Since</p>
                        <h4><?= !empty($patient['created_at']) ? date("d M, Y, h:i A", strtotime($patient['created_at'])) : 'N/A' ?>
                        </h4>
                    </div>
                    <div>
                        <p>Last Update</p>
                        <h4><?= !empty($patient['updated_at']) ? date("d M, Y, h:i A", strtotime($patient['updated_at'])) : 'N/A' ?>
                        </h4>
                    </div>
                </div>
            </div>
        </section>


        <section class="forth-sec">
            <h3>Login History</h3>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Sl no</th>
                            <th>Login</th>
                            <th>Logout</th>
                            <th>IP address</th>
                            <th>Device</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "SELECT * 
                                    FROM log_info 
                                    WHERE patient_id = $patient_id 
                                    AND log_type = 'patient' 
                                    AND status = 'Success' 
                                    ORDER BY login_time DESC";
                            
                            $result = mysqli_query($conn, $query);
                            $sl = 1;

                        ?>
                        <?php if (!empty($result) && mysqli_num_rows($result) > 0): ?>
                        <?php while ($log = mysqli_fetch_assoc($result)): ?>
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
        </section>

        <section class="fifth-sec">
            <h3>Danger Zone</h3>
            <p>Once you delete your account, there is no going back. Please be certain.</p>
            <form action="patient-delete-account.php" method="POST"
                onsubmit="return confirm('Are you sure you want to delete your account permanently?');">
                <button type="submit" name="delete_account">
                    <i class="fas fa-trash-alt"></i> Delete Account
                </button>
            </form>
        </section>

    </main>
    <?php include 'patient-footer.php';?>
</body>

</html>