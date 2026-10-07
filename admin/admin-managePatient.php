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
$status = $_GET['status'] ?? '';
$gender = $_GET['gender'] ?? '';
$blood_group = $_GET['blood_group'] ?? '';
$sort = $_GET['sort'] ?? 'latest';

// Initialize conditions array
$conditions = [];

// Handle search
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $conditions[] = "(name LIKE '%$search%' 
                      OR patient_id LIKE '%$search%' 
                      OR email LIKE '%$search%' 
                      OR phone LIKE '%$search%')";
}

// Handle status filter
if (!empty($status) && $status != 'All') {
    $status = mysqli_real_escape_string($conn, $status);
    $conditions[] = "availability = '$status'";
}

// Handle gender filter
if (!empty($gender) && $gender != 'All') {
    $gender = mysqli_real_escape_string($conn, $gender);
    $conditions[] = "gender = '$gender'";
}

// Handle blood group filter
if (!empty($blood_group) && $blood_group != 'All') {
    $blood_group = mysqli_real_escape_string($conn, $blood_group);
    $conditions[] = "blood_group = '$blood_group'";
}

// Build WHERE clause
$where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Handle sorting order
$order = $sort === 'oldest' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Patients</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet" />
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

    .table a {
        font-size: 16px;
        margin-right: 5px;
        cursor: pointer;
        text-decoration: none;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 20px;
        border-radius: 10px;
        width: 40%;
        position: relative;
        animation: fadeInModal .3s ease-in-out;
    }

    .close {
        position: absolute;
        right: 15px;
        top: 10px;
        font-size: 22px;
        cursor: pointer;
    }

    button {
        margin-top: 10px;
        padding: 8px 15px;
        border: none;
        background: #f43f5e;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background: #e11d48;
    }

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
    <?php include 'admin-header.php' ?>
    <main>
        <section class="first-sec">
            <div class="heading-part">
                <h2>Manage Patients</h2>
                <a href="" id="add-btn">Add Patients</a>
            </div>

            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar"
                        placeholder="Search by name, id, email, phone....."
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <select name="status">
                            <option value=""
                                <?= (isset($_GET['status']) && $_GET['status'] === '') ? 'selected' : '' ?>>All
                                Status</option>
                            <option value="Active"
                                <?= (isset($_GET['status']) && $_GET['status'] === 'Active') ? 'selected' : '' ?>>
                                Active</option>
                            <option value="Inactive"
                                <?= (isset($_GET['status']) && $_GET['status'] === 'Inactive') ? 'selected' : '' ?>>
                                Inactive</option>
                            <option value="Blocked"
                                <?= (isset($_GET['status']) && $_GET['status'] === 'Blocked') ? 'selected' : '' ?>>
                                Blocked</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="gender">
                            <option value=""
                                <?= (isset($_GET['gender']) && $_GET['gender'] === '') ? 'selected' : '' ?>>All
                                Gender</option>
                            <option value="Male"
                                <?= (isset($_GET['gender']) && $_GET['gender'] === 'Male') ? 'selected' : '' ?>>Male
                            </option>
                            <option value="Female"
                                <?= (isset($_GET['gender']) && $_GET['gender'] === 'Female') ? 'selected' : '' ?>>
                                Female</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="blood_group">
                            <option value=""
                                <?= (isset($_GET['blood_group']) && $_GET['blood_group'] === '') ? 'selected' : '' ?>>
                                All Group</option>
                            <option value="A+"
                                <?= (isset($_GET['blood_group']) && $_GET['blood_group'] === 'A+') ? 'selected' : '' ?>>
                                A+</option>
                            <option value="A-"
                                <?= (isset($_GET['blood_group']) && $_GET['blood_group'] === 'A-') ? 'selected' : '' ?>>
                                A-</option>
                            <option value="B+"
                                <?= (isset($_GET['blood_group']) && $_GET['blood_group'] === 'B+') ? 'selected' : '' ?>>
                                B+</option>
                            <option value="B-"
                                <?= (isset($_GET['blood_group']) && $_GET['blood_group'] === 'B-') ? 'selected' : '' ?>>
                                B-</option>
                            <option value="O+"
                                <?= (isset($_GET['blood_group']) && $_GET['blood_group'] === 'O+') ? 'selected' : '' ?>>
                                O+</option>
                            <option value="O-"
                                <?= (isset($_GET['blood_group']) && $_GET['blood_group'] === 'O-') ? 'selected' : '' ?>>
                                O-</option>
                            <option value="AB+"
                                <?= (isset($_GET['blood_group']) && $_GET['blood_group'] === 'AB+') ? 'selected' : '' ?>>
                                AB+</option>
                            <option value="AB-"
                                <?= (isset($_GET['blood_group']) && $_GET['blood_group'] === 'AB-') ? 'selected' : '' ?>>
                                AB-</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="sort">
                            <option value="latest"
                                <?= (isset($_GET['sort']) && $_GET['sort'] === 'latest') ? 'selected' : '' ?>>Latest
                                First</option>
                            <option value="oldest"
                                <?= (isset($_GET['sort']) && $_GET['sort'] === 'oldest') ? 'selected' : '' ?>>Oldest
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
                            <th>Patient</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Gender/Age</th>
                            <th>Blood Group</th>
                            <th>Status</th>
                            <th>Join</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         $query = "SELECT * FROM patient $where_clause ORDER BY created_at $order";
                           $result = $conn->query($query);
                          if (mysqli_num_rows($result) > 0) {
                            $i = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                              $dob = new DateTime($row['dob']);
                              $age = $dob->diff(new DateTime())->y;
                          ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>
                                <img src="../upload/patient/<?= $row['profile_img'] ?? 'user.jpg' ?>" alt="patient">
                                <strong><?= htmlspecialchars($row['name']) ?></strong>
                                <span>Id: <?= $row['patient_id'] ?></span>
                            </td>
                            <td>
                                <span><?= $row['area'] ?>, <?= $row['city'] ?></span>
                                <span><?= $row['state'] ?> - <?= $row['pincode'] ?></span>
                            </td>
                            <td>
                                <span><?= $row['email'] ?></span>
                                <span><?= $row['phone'] ?></span>
                            </td>
                            <td>
                                <span><?= ucfirst($row['gender']) ?></span>
                                <span><?= $age ?> years</span>
                            </td>
                            <td><?= $row['blood_group'] ?></td>
                            <td><?= ucfirst($row['availability']) ?></td>
                            <td><?= date('d/m/Y h:i A', strtotime($row['created_at'])) ?></td>
                            <td>
                                <a href="admin-view-patient.php?id=<?= $row['patient_id'] ?>">
                                    <i class="fa-solid fa-eye" title="View Profile" style="color: #2563eb;"></i>
                                </a>

                                <i class="fa-solid fa-ban" title="Patient Status" style="color: #f43f5e; cursor: pointer;"
                                        onclick="openModal(<?= $row['patient_id']; ?>)"></i>
                            </td>
                        </tr>
                        <?php }
                         } else { ?>
                        <tr>
                            <td colspan="9">No records found.</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- Modal for updating status -->
        <div id="availabilityModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h3>Update Patient Status</h3>
                <form id="availabilityForm" action="update-patientStatus.php" method="POST">
                    <input type="hidden" id="patientId" name="patient_id">

                    <label for="status">Select Availability:</label>
                    <select id="status" name="availability" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Blocked">Blocked</option>
                    </select>
                    <button type="submit">Update Status</button>
                </form>
            </div>
        </div>
    </main>
    <script>
    function openModal(patientID) {
        document.getElementById('patientId').value = patientID; // ✅ Ensure correct ID is used
        document.getElementById('availabilityModal').style.display = 'block'; // ✅ Ensure modal is set to visible
    }

    function closeModal() {
        document.getElementById('availabilityModal').style.display = 'none';
    }
    </script>
    <?php include 'admin-footer.php'; ?>
</body>

</html>