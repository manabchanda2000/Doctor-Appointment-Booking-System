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

// Handle search
if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $conditions[] = "(p.name LIKE '%$search%' OR c.clinic_name LIKE '%$search%')";
}
$where = implode(" AND ", $conditions);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Prescriptions Management</title>
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

    .sf-part {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .search-bar {
        flex: 1;
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


    #search-btn,
    #filter-btn {
        background-color: #3B82F6;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
    }

    #add-btn {
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 5px;
        background-color: #3B82F6;
        color: white;
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

    #view {
        color: #3B82F6;
    }

    #approve {
        color: #166534;
    }

    #cancle {
        color: #DC2626;
    }

    #download {
        color: #EAB308;
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
    #editPrescriptionForm .form-group {
        margin-bottom: 20px;
    }

    #editPrescriptionForm label {
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }

    #editPrescriptionForm input[type="file"] {
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
    <?php include 'doctor-header.php'?>
    <main>
        <section class="first-sec">
            <form method="" GET class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar" placeholder="Search by Patient, Clinic....."
                        value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button type="submit" id="search-btn">Search</button>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                $query = "SELECT 
                            pr.prescription_id, pr.prescription,
                            p.name AS patient_name,
                            p.gender,
                            p.dob,
                            p.email,
                            p.phone,
                            p.profile_img,
                            c.clinic_name,
                            a.appointment_date,
                            a.appointment_time
                        FROM prescription pr
                        JOIN appointment a ON pr.appointment_id = a.appointment_id
                        JOIN patient p ON pr.patient_id = p.patient_id
                        JOIN doctor_clinic c ON a.clinic_id = c.clinic_id
                        WHERE $where
                        ORDER BY pr.created_at DESC";

                $result = mysqli_query($conn, $query);
                $sl = 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $dob = new DateTime($row['dob']);
                        $today = new DateTime();
                        $age = $today->diff($dob)->y;

                        echo "<tr>
                            <td>{$sl}</td>
                            <td>
                                <img src='../upload/patient/{$row['profile_img']}' alt='patient'>
                                <h4>{$row['patient_name']}</h4>
                                <span>ID: {$row['prescription_id']}</span>
                            </td>
                            <td>{$age} / {$row['gender']}</td>
                            <td>
                                <span>{$row['email']}</span>
                                <span>{$row['phone']}</span>
                            </td>
                            <td>{$row['clinic_name']}</td>
                            <td>
                                <span>" . date('M d, Y', strtotime($row['appointment_date'])) . "</span>
                                <span>" . date('h:i A', strtotime($row['appointment_time'])) . "</span>
                            </td>
                            <td>
                                <a href='../upload/patient/{$row['prescription']}' target='_blank' 'id='download' title='Download'><i class='fa-solid fa-file-arrow-down'></i></a>
                                <a href='javascript:void(0);'data-prescription-id='{$row['prescription_id']}' id='approve' title='Edit' class='edit-prescription-btn' ><i class='fa-solid fa-pen-to-square'></i></a>
                                <a href='delete-prescription.php?id={$row['prescription_id']}' id='cancle' title='Delete' onclick='return confirm(\"Are you sure you want to delete this prescription?\")'><i class='fa-solid fa-trash'></i></a>
                            </td>
                        </tr>";
                        $sl++;
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center;'>No Prescription Found</td></tr>";
                }
                ?>
                    </tbody>
                </table>
            </div>

        </section>
        <!-- Modal Box -->
        <div id="editPrescriptionModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeEditModalBtn">&times;</span>
                <h2>Edit Prescription File</h2>

                <form id="editPrescriptionForm" method="POST" enctype="multipart/form-data"
                    action="update-prescription.php">
                    <!-- Hidden Field -->
                    <input type="hidden" name="prescription_id" id="modal_prescription_id">

                    <!-- File Upload -->
                    <div class="form-group">
                        <label for="file">Upload New File</label>
                        <input type="file" name="file" id="file" accept=".pdf,.jpg,.jpeg,.png,.docx" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="saveBtn">Update Prescription</button>
                </form>
            </div>
        </div>
    </main>
    <script>
    // Open Modal
    document.querySelectorAll('.edit-prescription-btn').forEach(button => {
        button.addEventListener('click', function() {
            const prescriptionId = this.getAttribute('data-prescription-id');
            document.getElementById('modal_prescription_id').value = prescriptionId;
            document.getElementById('editPrescriptionModal').style.display = 'block';
        });
    });

    // Close Modal
    document.getElementById('closeEditModalBtn').addEventListener('click', () => {
        document.getElementById('editPrescriptionModal').style.display = 'none';
    });

    // Close Modal when Clicking Outside
    window.onclick = function(event) {
        if (event.target === document.getElementById('editPrescriptionModal')) {
            document.getElementById('editPrescriptionModal').style.display = "none";
        }
    };
    </script>
    <?php include 'doctor-footer.php'; ?>
</body>

</html>