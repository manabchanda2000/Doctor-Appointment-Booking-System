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
    $conditions[] = "(dc.clinic_name LIKE '%$search%' 
                      OR d.name LIKE '%$search%' 
                      OR dc.area LIKE '%$search%'
                      OR dc.city LIKE '%$search%'
                      OR dc.state LIKE '%$search%'
                      OR dc.pincode LIKE '%$search%')";
}

// Handle status filter
if (!empty($_GET['status']) && $_GET['status'] != '') {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $conditions[] = "dc.status = '$status'";
}

// Handle days filter
if (!empty($_GET['days'])) {
    $days = array_map(function ($day) use ($conn) {
        return mysqli_real_escape_string($conn, $day);
    }, $_GET['days']);
    $day_conditions = array_map(function ($day) {
        return "dc.days_available LIKE '%$day%'";
    }, $days);
    $conditions[] = '(' . implode(' OR ', $day_conditions) . ')';
}

// Handle time range filters
if (!empty($_GET['from'])) {
    $from_time = mysqli_real_escape_string($conn, $_GET['from']);
    $conditions[] = "dc.opening_time <= '$from_time'";
}
if (!empty($_GET['to'])) {
    $to_time = mysqli_real_escape_string($conn, $_GET['to']);
    $conditions[] = "dc.closing_time >= '$to_time'";
}

// Build WHERE clause
$where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Handle sorting order
$order = ($_GET['order'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Clinic</title>
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

    .filter-group {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-group label {
        font-size: 12px;
    }

    .filter-group select,
    input[type="time"] {
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
                <h2>Manage Clinics</h2>
            </div>
            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" id="search-bar" name="search"
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                        placeholder="Search by Clinic name, doctor name, address.....">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active
                            </option>
                            <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                                Inactive</option>
                            <option value="blocked" <?= ($_GET['status'] ?? '') === 'blocked' ? 'selected' : '' ?>>
                                Blocked</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Mon</label>
                        <input type="checkbox" name="days[]" value="Mon"
                            <?= isset($_GET['days']) && in_array('Mon', $_GET['days']) ? 'checked' : '' ?>>
                        <label>Tue</label>
                        <input type="checkbox" name="days[]" value="Tue"
                            <?= isset($_GET['days']) && in_array('Tue', $_GET['days']) ? 'checked' : '' ?>>
                        <label>Wed</label>
                        <input type="checkbox" name="days[]" value="Wed"
                            <?= isset($_GET['days']) && in_array('Wed', $_GET['days']) ? 'checked' : '' ?>>
                        <label>Thu</label>
                        <input type="checkbox" name="days[]" value="Thu"
                            <?= isset($_GET['days']) && in_array('Thu', $_GET['days']) ? 'checked' : '' ?>>
                        <label>Fri</label>
                        <input type="checkbox" name="days[]" value="Fri"
                            <?= isset($_GET['days']) && in_array('Fri', $_GET['days']) ? 'checked' : '' ?>>
                        <label>Sat</label>
                        <input type="checkbox" name="days[]" value="Sat"
                            <?= isset($_GET['days']) && in_array('Sat', $_GET['days']) ? 'checked' : '' ?>>
                        <label>Sun</label>
                        <input type="checkbox" name="days[]" value="Sun"
                            <?= isset($_GET['days']) && in_array('Sun', $_GET['days']) ? 'checked' : '' ?>>
                    </div>
                    <div class="filter-group">
                        <label>From</label>
                        <input type="time" name="from" value="<?= htmlspecialchars($_GET['from'] ?? '') ?>">
                    </div>
                    <div class="filter-group">
                        <label>To</label>
                        <input type="time" name="to" value="<?= htmlspecialchars($_GET['to'] ?? '') ?>">
                    </div>
                    <div class="filter-group">
                        <select name="order">
                            <option value="DESC" <?= ($_GET['order'] ?? '') === 'DESC' ? 'selected' : '' ?>>Latest First
                            </option>
                            <option value="ASC" <?= ($_GET['order'] ?? '') === 'ASC' ? 'selected' : '' ?>>Oldest First
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
                            <th>Clinic</th>
                            <th>Doctor</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Available Days</th>
                            <th>Time Slot</th>
                            <th>Fees</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          // Fetch clinic data with doctor info
                          $sql = "SELECT dc.*, d.name AS doctor_name, d.doctor_id AS doc_id, d.profile_img, d.phone, d.email 
                          FROM doctor_clinic dc
                          JOIN doctor d ON dc.doctor_id = d.doctor_id 
                          $where_clause
                          ORDER BY dc.created_at $order";
                        $result = $conn->query($sql);
                              if ($result->num_rows > 0) {
                                  $sl = 1;
                                  while ($row = $result->fetch_assoc()) {
                                      ?>
                        <tr>
                            <td><?php echo $sl++; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['clinic_name']); ?></strong>
                                <span>Id: <?php echo $row['clinic_id']; ?></span>
                            </td>
                            <td>
                                <img src="../upload/doctor/<?php echo !empty($row['profile_img']) ? $row['profile_img'] : 'user.jpg'; ?>"
                                    alt="doc">
                                <strong><?php echo htmlspecialchars($row['doctor_name']); ?></strong>
                                <span>Id: <?php echo $row['doc_id']; ?></span>
                            </td>
                            <td>
                                <span><?php echo htmlspecialchars($row['area']); ?>,
                                    <?php echo htmlspecialchars($row['city']); ?></span>
                                <span><?php echo htmlspecialchars($row['state']); ?> -
                                    <?php echo htmlspecialchars($row['pincode']); ?></span><br>
                            </td>
                            <td>
                                <span><?php echo htmlspecialchars($row['email']); ?></span>
                                <span><?php echo htmlspecialchars($row['phone']); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($row['days_available']); ?></td>
                            <td><?php echo date("g:i A", strtotime($row['opening_time'])) . " - " . date("g:i A", strtotime($row['closing_time'])); ?>
                            </td>
                            <td><i class="fa-solid fa-indian-rupee-sign"></i> <?php echo $row['fees']; ?></td>
                            <td><?php echo ucfirst($row['status'] ?? 'active'); ?></td>
                            <td><?php echo date("d/m/Y g:i A", strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="../doctor/clinic-view.php?clinic_id=<?= $row['clinic_id']; ?>"><i
                                        class="fa-solid fa-eye" title="View Clinic" style="color: #2563eb;"></i></a>

                                <i class="fa-solid fa-ban" title="Clinic Status" style="color: #f43f5e;"
                                    onclick="openModal(<?= $row['clinic_id']; ?>)"></i>

                               
                            </td>
                        </tr>
                        <?php
                        }
                    } else {
                        echo "<tr><td colspan='11'>No clinic records found.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <!-- Modal for updating status -->
    <div id="availabilityModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Update Clinic Status</h3>
            <form id="availabilityForm" action="update-clinicStatus.php" method="POST">
                <input type="hidden" id="clinicId" name="clinic_id">

                <label for="status">Select Availability:</label>
                <select id="status" name="status" required>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Blocked">Blocked</option>
                </select>
                <button type="submit">Update Status</button>
            </form>
        </div>
    </div>

    <script>
    function openModal(clinicID) {
        document.getElementById('clinicId').value = clinicID; // ✅ Ensure correct ID is used
        document.getElementById('availabilityModal').style.display = 'block'; // ✅ Ensure modal is set to visible
    }

    function closeModal() {
        document.getElementById('availabilityModal').style.display = 'none';
    }



    function confirmDelete(clinicId) {
        if (confirm("Are you sure you want to delete this clinic?")) {
            window.location.href = `admin-deleteClinic.php?clinic_id=${clinicId}`;
        }
    }
    </script>
    <?php include 'admin-footer.php'; ?>


</body>

</html>