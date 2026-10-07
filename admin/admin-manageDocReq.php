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
    $conditions[] = "(name LIKE '%$search%' 
                      OR email LIKE '%$search%' 
                      OR phone LIKE '%$search%' 
                      OR UID LIKE '%$search%')";
}

// Handle status filter
if (!empty($_GET['status']) && $_GET['status'] != 'All Status') {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $conditions[] = "status = '$status'";
}

// Handle gender filter
if (!empty($_GET['gender']) && $_GET['gender'] != 'All Gender') {
    $gender = mysqli_real_escape_string($conn, $_GET['gender']);
    $conditions[] = "gender = '$gender'";
}

// Handle date range filters
if (!empty($_GET['from'])) {
    $from_date = mysqli_real_escape_string($conn, $_GET['from']);
    $conditions[] = "DATE(requested_at) >= '$from_date'";
}
if (!empty($_GET['to'])) {
    $to_date = mysqli_real_escape_string($conn, $_GET['to']);
    $conditions[] = "DATE(requested_at) <= '$to_date'";
}

// Build WHERE clause
$where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Handle sorting order
$order = ($_GET['order'] ?? 'DESC') == 'ASC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Request</title>
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
    <?php include 'admin-header.php'; ?>

    <main>
        <section class="first-sec">
            <div class="heading-part">
                <h2>Doctor Request</h2>
            </div>

            <!-- Filter + Search Form -->
            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" id="search-bar" name="search"
                        placeholder="Search by name, email, phone, UID..."
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <select name="status">
                            <option
                                <?= (!isset($_GET['status']) || $_GET['status'] == "All Status") ? "selected" : "" ?>>
                                All Status</option>
                            <option <?= ($_GET['status'] ?? "") == "Pending" ? "selected" : "" ?>>Pending</option>
                            <option <?= ($_GET['status'] ?? "") == "Approved" ? "selected" : "" ?>>Approved</option>
                            <option <?= ($_GET['status'] ?? "") == "Rejected" ? "selected" : "" ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="gender">
                            <option
                                <?= (!isset($_GET['gender']) || $_GET['gender'] == "All Gender") ? "selected" : "" ?>>
                                All Gender</option>
                            <option <?= ($_GET['gender'] ?? "") == "Male" ? "selected" : "" ?>>Male</option>
                            <option <?= ($_GET['gender'] ?? "") == "Female" ? "selected" : "" ?>>Female</option>
                            <option <?= ($_GET['gender'] ?? "") == "Other" ? "selected" : "" ?>>Other</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>From</label>
                        <input type="date" name="from" value="<?= htmlspecialchars($_GET['from'] ?? '') ?>">
                    </div>
                    <div class="filter-group">
                        <label>To</label>
                        <input type="date" name="to" value="<?= htmlspecialchars($_GET['to'] ?? '') ?>">
                    </div>
                    <div class="filter-group">
                        <select name="order">
                            <option value="DESC" <?= ($_GET['order'] ?? "") == "DESC" ? "selected" : "" ?>>Latest First
                            </option>
                            <option value="ASC" <?= ($_GET['order'] ?? "") == "ASC" ? "selected" : "" ?>>Oldest First
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
                            <th>Name</th>
                            <th>UID</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Requested</th>
                            <th>Response</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                  $query = "SELECT * FROM doctor_request $where_clause ORDER BY requested_at $order";
                  $result = $conn->query($query);
                  if ($result->num_rows > 0) {
                    $sl = 1;
                    while ($row = $result->fetch_assoc()) {
                        $approved_at = $row['approved_at'] ? date('d/m/Y h:i A', strtotime($row['approved_at'])) : '-';
                        $requested_at = date('d/m/Y h:i A', strtotime($row['requested_at']));
                echo "<tr>
                    <td>{$sl}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['UID']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['gender']}</td>
                    <td>{$requested_at}</td>
                    <td>{$approved_at}</td>
                    <td>{$row['status']}</td>
                    <td>
                        <a href='admin-response.php?id={$row['request_id']}&action=approve'><i class='fa-solid fa-circle-check' title='Approve' style='color: #22c55e;'></i></a>
                        <a href='admin-response.php?id={$row['request_id']}&action=reject'><i class='fa-solid fa-circle-xmark' title='Reject' style='color: #ef4444;'></i></a>
                    </td>
                </tr>";
                $sl++;
            }
          } else {
            echo "<tr><td colspan='10'>No doctor requests found.</td></tr>";
          }
                ?>

                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <?php include 'admin-footer.php'; ?>
</body>

</html>