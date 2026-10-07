<?php
include '../connection.php';
session_start();

// Verify user authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.html");
    exit();
}

$admin_id = $_SESSION['admin_id'];
// Collect inputs from the GET method
$search = trim($_GET['search'] ?? '');
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';
$order = $_GET['order'] ?? 'latest';

// Initialize an array for conditions
$conditions = [];

// Add search condition
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $conditions[] = "(name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%')";
}

// Add date range conditions
if (!empty($from_date)) {
    $conditions[] = "created_at >= '$from_date'";
}
if (!empty($to_date)) {
    $conditions[] = "created_at <= '$to_date'";
}

// Combine conditions into the WHERE clause
$where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Set order condition
$order_by = ($order === 'oldest') ? 'ORDER BY created_at ASC' : 'ORDER BY created_at DESC';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Query</title>
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
                <h2>Manage Query</h2>
                <!-- <a href="" id="add-btn">Add Patients</a> -->
            </div>
            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar"
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                        placeholder="Search by name, email, phone.....">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <label for="from-date">From</label>
                        <input type="date" name="from_date" id="from-date"
                            value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>">
                    </div>
                    <div class="filter-group">
                        <label for="to-date">To</label>
                        <input type="date" name="to_date" id="to-date"
                            value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>">
                    </div>
                    <div class="filter-group">
                        <select name="order">
                            <option value="latest"
                                <?= (isset($_GET['order']) && $_GET['order'] === 'latest') ? 'selected' : '' ?>>Latest
                                First</option>
                            <option value="oldest"
                                <?= (isset($_GET['order']) && $_GET['order'] === 'oldest') ? 'selected' : '' ?>>Oldest
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Created</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    // Fetch data from 'query' table
                      // Build the final query
                      $query = "SELECT * FROM query $where_clause $order_by";
                      $result = $conn->query($query);

                      if ($result->num_rows > 0) {
                          $sl = 1; // Serial number counter
                          while ($row = $result->fetch_assoc()) {
                              $created_at = date('d/m/Y h:i A', strtotime($row['created_at']));
                              echo "<tr>
                                  <td>{$sl}</td>
                                  <td>{$row['name']}</td>
                                  <td>{$row['email']}</td>
                                  <td>{$row['phone']}</td>
                                  <td>{$row['message']}</td>
                                  <td>{$created_at}</td>
                                  
                              </tr>";
                              $sl++;
                          }
                      } else {
                          echo "<tr><td colspan='7'>No queries found.</td></tr>";
                      }
                      ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

</body>

</html>