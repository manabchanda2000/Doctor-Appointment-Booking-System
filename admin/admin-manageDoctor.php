<?php
include '../connection.php';
session_start();

// Verify user authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.html");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Initialize conditions array
$conditions = [];

// Handle search
if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
    $conditions[] = "(doctor_id LIKE '%$search%' OR 
                      UID LIKE '%$search%' OR 
                      name LIKE '%$search%' OR 
                      email LIKE '%$search%' OR 
                      phone LIKE '%$search%')";
}

// Handle specialization filter
if (!empty($_GET['specialization'])) {
    $specialization = mysqli_real_escape_string($conn, $_GET['specialization']);
    $conditions[] = "specialization = '$specialization'";
}

// Handle experience filter
if (!empty($_GET['experience'])) {
    $experience = (int) $_GET['experience'];
    $conditions[] = "experience > $experience";
}

// Handle rating filter
if (!empty($_GET['rating'])) {
    $rating = (float) $_GET['rating'];
    $conditions[] = "rating >= $rating";
}

// Handle availability filter
if (!empty($_GET['availability'])) {
    $availability = mysqli_real_escape_string($conn, $_GET['availability']);
    $conditions[] = "availability = '$availability'";
}

// Handle gender filter
if (!empty($_GET['gender'])) {
    $gender = mysqli_real_escape_string($conn, $_GET['gender']);
    $conditions[] = "gender = '$gender'";
}

// Handle emergency filter
if (!empty($_GET['emergency'])) {
    $emergency = mysqli_real_escape_string($conn, $_GET['emergency']);
    $conditions[] = "emergency = '$emergency'";
}

// Build WHERE clause
$where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Handle sorting order
$order = ($_GET['order'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctor</title>
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
        justify-content: space-between;
        gap: 10px;
        width: 100%;
        flex-wrap: wrap;
    }

    .filter-group select {
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

    tbody a {
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
    <?php include 'admin-header.php'; ?>
    <main>
        <section class="first-sec">
            <div class="heading-part">
                <h2>Manage Doctors</h2>
                <a href="" id="add-btn">Add Doctors</a>
            </div>
            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" id="search-bar" name="search"
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                        placeholder="Search by Doctor id, UID, name, email, phone.....">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <select name="specialization">
                            <option value="">All Specialization</option>
                            <option value="Cardiology"
                                <?= ($_GET['specialization'] ?? '') === 'Cardiology' ? 'selected' : '' ?>>Cardiology
                            </option>
                            <option value="Neurology"
                                <?= ($_GET['specialization'] ?? '') === 'Neurology' ? 'selected' : '' ?>>Neurology
                            </option>
                            <option value="Orthopedics"
                                <?= ($_GET['specialization'] ?? '') === 'Orthopedics' ? 'selected' : '' ?>>Orthopedics
                            </option>
                            <option value="Pediatrics"
                                <?= ($_GET['specialization'] ?? '') === 'Pediatrics' ? 'selected' : '' ?>>Pediatrics
                            </option>
                            <option value="Dermatology"
                                <?= ($_GET['specialization'] ?? '') === 'Dermatology' ? 'selected' : '' ?>>Dermatology
                            </option>
                            <option value="Psychiatry"
                                <?= ($_GET['specialization'] ?? '') === 'Psychiatry' ? 'selected' : '' ?>>Psychiatry
                            </option>
                            <option value="General Surgery"
                                <?= ($_GET['specialization'] ?? '') === 'General Surgery' ? 'selected' : '' ?>>General
                                Surgery</option>
                            <option value="Radiology"
                                <?= ($_GET['specialization'] ?? '') === 'Radiology' ? 'selected' : '' ?>>Radiology
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="experience">
                            <option value="">All Experience</option>
                            <option value="0" <?= ($_GET['experience'] ?? '') === '0' ? 'selected' : '' ?>>Fresher
                            </option>
                            <option value="2" <?= ($_GET['experience'] ?? '') === '2' ? 'selected' : '' ?>>2+ years
                            </option>
                            <option value="5" <?= ($_GET['experience'] ?? '') === '5' ? 'selected' : '' ?>>5+ years
                            </option>
                            <option value="10" <?= ($_GET['experience'] ?? '') === '10' ? 'selected' : '' ?>>10+ years
                            </option>
                            <option value="15" <?= ($_GET['experience'] ?? '') === '15' ? 'selected' : '' ?>>15+ years
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="rating">
                            <option value="">All Ratings</option>
                            <option value="1" <?= ($_GET['rating'] ?? '') === '1' ? 'selected' : '' ?>>1+</option>
                            <option value="2" <?= ($_GET['rating'] ?? '') === '2' ? 'selected' : '' ?>>2+</option>
                            <option value="3" <?= ($_GET['rating'] ?? '') === '3' ? 'selected' : '' ?>>3+</option>
                            <option value="4" <?= ($_GET['rating'] ?? '') === '4' ? 'selected' : '' ?>>4+</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="availability">
                            <option value="">All Type</option>
                            <option value="active" <?= ($_GET['availability'] ?? '') === 'active' ? 'selected' : '' ?>>
                                Active</option>
                            <option value="inactive"
                                <?= ($_GET['availability'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            <option value="blocked"
                                <?= ($_GET['availability'] ?? '') === 'blocked' ? 'selected' : '' ?>>Blocked</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="gender">
                            <option value="">All Gender</option>
                            <option value="male" <?= ($_GET['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male
                            </option>
                            <option value="female" <?= ($_GET['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="emergency">
                            <option value="">All Doctors</option>
                            <option value="yes" <?= ($_GET['emergency'] ?? '') === 'yes' ? 'selected' : '' ?>>Emergency:
                                yes</option>
                            <option value="no" <?= ($_GET['emergency'] ?? '') === 'no' ? 'selected' : '' ?>>Emergency:
                                no</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="order">
                            <option value="desc" <?= ($_GET['order'] ?? '') === 'desc' ? 'selected' : '' ?>>Latest First
                            </option>
                            <option value="asc" <?= ($_GET['order'] ?? '') === 'asc' ? 'selected' : '' ?>>Oldest First
                            </option>
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
                            <th>Doctor Id/UID</th>
                            <th>Doctor</th>
                            <th>Contact</th>
                            <th>Gender/Age</th>
                            <th>Address</th>
                            <th>Experience</th>
                            <th>Qualifications</th>
                            <th>Ratings</th>
                            <th>Fees</th>
                            <th>Availability/Emergency</th>
                            <th>Join</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    $query = "SELECT * FROM doctor $where_clause ORDER BY created_at $order";

              $result = $conn->query($query);
              if ($result->num_rows > 0) {
                $sl = 1;
                while ($row = $result->fetch_assoc()) {
                    $profile_img = !empty($row['profile_img']) ? "../upload/doctor/" . $row['profile_img'] : "../img/default-user.jpg";
                    $age = date_diff(date_create($row['dob']), date_create('today'))->y;
                    $availability = ucfirst($row['availability']);
                    $emergency = ucfirst($row['emergency']);
                    $rating = $row['rating'] . " (" . $row['total_reviews'] . " reviews)";
                    $created_at = date('d/m/Y h:i A', strtotime($row['created_at']));

                    echo "<tr>
                            <td>{$sl}</td>
                            <td>
                                <span class='id'>Id: {$row['doctor_id']}</span>
                                <span class='id'>UID: {$row['UID']}</span>
                            </td>
                            <td>
                                <img src='{$profile_img}' alt='doc'>
                                <strong>{$row['name']}</strong>
                                <span>{$row['specialization']}</span>
                            </td>
                            <td>
                                <span class='contact'>{$row['email']}</span>
                                <span class='contact'>{$row['phone']}</span>
                            </td>
                            <td>
                                <span>{$row['gender']} / {$age} years</span>
                            </td>
                            <td>{$row['address']}</td>
                            <td>{$row['experience']} years</td>
                            <td>{$row['qualification']}</td>
                            <td>
                                <strong>{$rating}</strong>
                            </td>
                            <td>
                                <i class='fa-solid fa-indian-rupee-sign'></i>{$row['fees']}
                            </td>
                            <td>
                                <span class='status'>{$availability} / {$emergency}</span>
                            </td>
                            <td>{$created_at}</td>
                            <td>
                               <a href='admin-view-doctor.php?id={$row['doctor_id']}' >
                                <i class='fa-solid fa-eye' title='View Profile' id='view' style='color: #2563eb;'></i></a>
                                <i class='fa-solid fa-ban' title='User Availability' id='block' style='color: #f43f5e;' onclick='openModal({$row['doctor_id']})'></i>                                
                                                             
                            </td>
                        </tr>";
                        $sl++;
                        }
                        } else {
                        echo "<tr>
                            <td colspan='13'>No doctor records found.</td>
                        </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- Modal for updating status -->
        <div id="availabilityModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h3>Update Doctor Availability</h3>
                <form id="availabilityForm" action="update-status.php" method="POST">
                    <input type="hidden" id="doctorId" name="doctor_id">

                    <label for="status">Select Availability:</label>
                    <select id="status" name="availability" required>
                        <option value="active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Blocked">Blocked</option>
                    </select>

                    <button type="submit">Update Status</button>
                </form>
            </div>
        </div>
    </main>
    <script>
    // Open Modal Function
    function openModal(doctorId) {
        document.getElementById('doctorId').value = doctorId; // Pass doctor ID to the form
        document.getElementById('availabilityModal').style.display = 'block'; // Show modal
    }

    // Close Modal Function
    function closeModal() {
        document.getElementById('availabilityModal').style.display = 'none'; // Hide modal
    }

    function confirmDelete(doctorId) {
    if (confirm("Are you sure you want to delete this doctor?")) {
        window.location.href = `admin-deleteDoctor.php?doctor_id=${doctorId}`;
    }
}

    </script>
<?php include 'admin-footer.php'; ?>
<!-- <i class='fa-solid fa-trash' title='Delete User' id='delete' style='color: #dc2626;' onclick='confirmDelete({$row['doctor_id']})'></i>    -->
</body>

</html>