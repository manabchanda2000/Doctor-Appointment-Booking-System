<?php
session_start();
include '../connection.php'; // DB connection

// Check login
if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];
$conditions = ["a.doctor_id = '$doctor_id'"];

// Handle tab (Upcoming / Past)
if (isset($_GET['tab']) && $_GET['tab'] === 'past') {
    $conditions[] = "a.status <> 'Scheduled'";
} else {
    $conditions[] = "a.status = 'Scheduled'";
}

// Handle search
if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $conditions[] = "(p.name LIKE '%$search%' OR c.clinic_name LIKE '%$search%')";
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

// Handle payment filter
if (!empty($_GET['payment'])) {
    $payment = mysqli_real_escape_string($conn, $_GET['payment']);
    $conditions[] = "a.payment_status = '$payment'";
}

$where_clause = implode(" AND ", $conditions);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
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
        margin-bottom: 20px;
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

    /* second section------------------ */
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
        border-bottom: 1px solid #ddd;
        white-space: nowrap;
        text-align: center;
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

    .table img {
        height: 30px;
        width: 30px;
        border-radius: 50%;
        object-fit: fill;
    }

    .table span {
        display: block;
    }

    .table a {
        text-decoration: none;
        font-size: 18px;
        margin-right: 5px;
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

    #view {
        color: #3B82F6;
    }

    #approve {
        color: #166534;
    }

    #cancle {
        color: #DC2626;
    }

    .prescription-btn.add {
        background: #28a745;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .prescription-btn.added {
        background: #6c757d;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: not-allowed;
    }

    /* Modal Overlay */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        z-index: 999;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background: rgba(0, 0, 0, 0.4);
        /* Black background with transparency */
        backdrop-filter: blur(3px);
        /* Blur effect */
    }

    /* Modal Content Box */
    .modal-content {
        background-color: #fff;
        margin: auto;
        padding: 30px 25px;
        border: 1px solid #ccc;
        width: 90%;
        max-width: 500px;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        position: relative;
        animation: fadeInModal 0.3s ease-in-out;
    }

    /* Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #333;
        text-decoration: none;
    }

    /* Form Elements */
    #prescriptionForm .form-group {
        margin-bottom: 20px;
    }

    #prescriptionForm label {
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }

    #prescriptionForm textarea {
        width: 100%;
        min-height: 120px;
        resize: vertical;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    #prescriptionForm input[type="file"] {
        display: block;
        margin-top: 8px;
    }

    #saveBtn {
        background-color: #007BFF;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        transition: .3s ease;
    }

    #saveBtn:hover {
        background-color: #0059b9;
    }

    /* Animation */
    @keyframes fadeInModal {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
</head>

<body>
    <?php include 'doctor-header.php'; ?>
    <main>
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
                <input type="hidden" name="tab" id="tab-input"
                    value="<?php echo isset($_GET['tab']) ? $_GET['tab'] : 'upcoming'; ?>">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar"
                        placeholder="Search by Patient, Clinic....."
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
                    <div class="filter-group">
                        <select name="payment">
                            <option value="">All Payments</option>
                            <option value="Pending" <?php if(@$_GET['payment'] == 'Pending') echo 'selected'; ?>>Pending
                            </option>
                            <option value="Paid" <?php if(@$_GET['payment'] == 'Paid') echo 'selected'; ?>>Paid</option>
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
                            <th>Patient</th>
                            <th>Age/Gender</th>
                            <th>Contact</th>
                            <th>Clinic</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Confirmation</th>
                            <th>Payment</th>
                            <th>Add Prescription</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "SELECT 
                                a.*, 
                                p.name AS patient_name,
                                p.gender,
                                p.dob,
                                p.email,
                                p.phone,
                                p.profile_img,
                                c.clinic_name,
                                pr.prescription_id
                            FROM appointment a
                            JOIN patient p ON a.patient_id = p.patient_id
                            JOIN doctor_clinic c ON a.clinic_id = c.clinic_id
                            LEFT JOIN prescription pr ON a.appointment_id = pr.appointment_id
                            WHERE $where_clause
                            ORDER BY a.created_at ASC";

                            $result = mysqli_query($conn, $query);
                            $sl = 1;
                            if (mysqli_num_rows($result) > 0) {
                                while ($app = mysqli_fetch_assoc($result)) {
                                    $dob = new DateTime($app['dob']);
                                    $today = new DateTime();
                                    $age = $today->diff($dob)->y;

                                    echo "<tr>
                                        <td>{$sl}</td>
                                        <td>
                                            <img src='../upload/patient/{$app['profile_img']}' alt='man'>
                                            <h4>{$app['patient_name']}</h4>
                                            <span>{$app['patient_id']}</span>
                                        </td>
                                        <td>{$age} / {$app['gender']}</td>
                                        <td>
                                            <span>{$app['email']}</span>
                                            <span>{$app['phone']}</span>
                                        </td>
                                        <td>{$app['clinic_name']}</td>
                                        <td>
                                            <span>" . date("M d, Y", strtotime($app['appointment_date'])) . "</span>
                                            <span>" . date("h:i A", strtotime($app['appointment_time'])) . "</span>
                                        </td>
                                        <td><span class='status'>{$app['status']}</span></td>
                                        <td><span class='confirmation'>{$app['doctor_confirmation']}</span></td>
                                        <td><span class='payment'>{$app['payment_status']}</span></td>
                                        <td>";

                                        if (empty($app['prescription_id'])) {
                                            echo "<button class='prescription-btn add' 
                                                    data-appointment-id='{$app['appointment_id']}' 
                                                    data-patient-id='{$app['patient_id']}' 
                                                    data-doctor-id='{$doctor_id}'>
                                                    Add
                                                </button>";
                                        } else {
                                            echo "<button class='prescription-btn added' disabled>Added</button>";
                                        }

                                        echo "</td>
                                        <td>
                                            <a href='../patient/view-appointment.php?id={$app['appointment_id']}' id='view' title='View'><i class='fa-solid fa-eye'></i></a>
                                            <a href='doctor-appointmentApproved.php?id={$app['appointment_id']}' id='approve' title='Approved' onclick='return confirm(\"Are you sure you want to approve this appointment?\")'><i class='fa-solid fa-circle-check'></i></a>
                                            <a href='doctor-appointmentReject.php?id={$app['appointment_id']}' id='cancle' title='Rejected' onclick='return confirm(\"Are you sure you want to Reject this appointment?\")'><i class='fa-solid fa-circle-xmark'></i></a>
                                            <a href='doctor-appointmentComplete.php?id={$app['appointment_id']}' id='complete' title='Mark as Completed' onclick='return confirm(\"Are you sure the appointment is completed? This will finalize payment.\")'><i class='fa-solid fa-clipboard-check'></i></a>
                                        </td>
                                    </tr>";
                                    $sl++;
                                }
                            } else {
                                echo "<tr><td colspan='11' style='text-align:center;'>No Appointments Found</td></tr>";
                            }
                            ?>
                    </tbody>

                </table>
            </div>
        </section>
        <!-- Prescription Modal -->
        <div id="prescriptionModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeModalBtn">&times;</span>
                <h2>Add Prescription</h2>

                <form id="prescriptionForm" method="POST" enctype="multipart/form-data" action="save-prescription.php">
                    <input type="hidden" name="appointment_id" id="modal_appointment_id">
                    <input type="hidden" name="patient_id" id="modal_patient_id">
                    <input type="hidden" name="doctor_id" id="modal_doctor_id">

                    <div class="form-group">
                        <label for="file">Attach File</label>
                        <input type="file" name="file" id="file" required>
                    </div>

                    <button type="submit" id="saveBtn">Save Prescription</button>
                </form>
            </div>
        </div>
    </main>
    <script>
    function setTab(tabName) {
        document.getElementById('tab-input').value = tabName;
        document.querySelector('form.sf-part').submit();
    }

    // Open Modal on Button Click
    document.querySelectorAll('.prescription-btn.add').forEach(function(button) {
        button.addEventListener('click', function() {
            var appointmentId = this.getAttribute('data-appointment-id');
            var patientId = this.getAttribute('data-patient-id');
            var doctorId = this.getAttribute('data-doctor-id');

            openPrescriptionModal(appointmentId, patientId, doctorId);
        });
    });

    // Modal open function
    function openPrescriptionModal(appointmentId, patientId, doctorId) {
        document.getElementById('modal_appointment_id').value = appointmentId;
        document.getElementById('modal_patient_id').value = patientId;
        document.getElementById('modal_doctor_id').value = doctorId;
        document.getElementById('prescriptionModal').style.display = 'block';
    }

    // Close Modal
    document.getElementById('closeModalBtn').addEventListener('click', function() {
        document.getElementById('prescriptionModal').style.display = 'none';
    });

    // Close Modal on outside click
    window.onclick = function(event) {
        if (event.target == document.getElementById('prescriptionModal')) {
            document.getElementById('prescriptionModal').style.display = "none";
        }
    }
    </script>
    <?php include 'doctor-footer.php'; ?>
</body>

</html>