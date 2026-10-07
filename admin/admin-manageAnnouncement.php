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
$category = trim($_GET['category'] ?? '');
$posted_by = trim($_GET['posted_by'] ?? '');
$posted_for = trim($_GET['posted_for'] ?? '');
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';
$order = $_GET['order'] ?? 'latest';

// Initialize conditions array
$conditions = [];

// Add search condition
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $conditions[] = "(title LIKE '%$search%')";
}

// Add category condition
if (!empty($category)) {
    $conditions[] = "category = '$category'";
}

// Add posted_by condition
if (!empty($posted_by)) {
    $conditions[] = "posted_by = '$posted_by'";
}

// Add posted_for condition
if (!empty($posted_for)) {
    $conditions[] = "posted_for = '$posted_for'";
}

// Add date range conditions
if (!empty($from_date)) {
    $conditions[] = "created_at > '$from_date'";
}
if (!empty($to_date)) {
    $conditions[] = "created_at < '$to_date'";
}

// Combine conditions into WHERE clause
$where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Set order condition
$order_by = ($order === 'oldest') ? 'ORDER BY created_at ASC' : 'ORDER BY created_at DESC';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcement</title>
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

    .notice-part {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
        gap: 10px;
    }

    .notice-box {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        background-color: #fdfdfd;
        box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #BFDBFE;
        margin-bottom: 20px;
        width: 100%;
    }

    .box-top {
        display: flex;
        align-items: center;
        /* justify-content: space-between; */
        width: 100%;
        gap: 20px;
    }

    .box-top #catagory {
        padding: 5px 10px;
        font-size: 14px;
        background-color: #f9c7c7;
        color: #970000;
        border-radius: 20px;
    }

    .box-top small,
    strong {
        color: #4B5563;
        font-size: 12px;
    }

    #id {
        color: #4B5563;
        font-size: 14px;
    }

    .box-middle h3 {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .box-middle p {
        width: 100%;
        color: #4B5563;
        text-align: justify;
        line-height: 1.4;
        font-size: 14px;
    }

    .box-bottom {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-top: 10px;
    }

    .box-bottom #file {
        text-decoration: none;
        padding: 5px 12px;
        background-color: #2563EB;
        color: white;
        border-radius: 5px;
    }

    #view {
        color: #2563eb;
    }

    #edit {
        color: #f59e0b;
    }

    #delete {
        color: #dc2626;
    }
    </style>
</head>

<body>
    <?php include 'admin-header.php'?>
    <main>
        <section class="first-sec">
            <div class="heading-part">
                <h2>Manage Announcement</h2>
                <a href="" id="add-btn">Add Announcement</a>
            </div>
            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar"
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Search by name, id.....">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <select name="category">
                            <option value="" <?= empty($_GET['category']) ? 'selected' : '' ?>>All Categories</option>
                            <option value="Emergency Alerts"
                                <?= (isset($_GET['category']) && $_GET['category'] == 'Emergency Alerts') ? 'selected' : '' ?>>
                                Emergency Alerts</option>
                            <option value="Healthcare Updates"
                                <?= (isset($_GET['category']) && $_GET['category'] == 'Healthcare Updates') ? 'selected' : '' ?>>
                                Healthcare Updates</option>
                            <option value="Maintenance"
                                <?= (isset($_GET['category']) && $_GET['category'] == 'Maintenance') ? 'selected' : '' ?>>
                                Maintenance</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="posted_by">
                            <option value="" <?= empty($_GET['posted_by']) ? 'selected' : '' ?>>All Posted by</option>
                            <option value="Doctor"
                                <?= (isset($_GET['posted_by']) && $_GET['posted_by'] == 'Doctor') ? 'selected' : '' ?>>
                                Doctor
                            </option>
                            <option value="Admin"
                                <?= (isset($_GET['posted_by']) && $_GET['posted_by'] == 'Admin') ? 'selected' : '' ?>>
                                Admin
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="posted_for">
                            <option value="" <?= empty($_GET['posted_for']) ? 'selected' : '' ?>>All Posted For</option>
                            <option value="Doctor"
                                <?= (isset($_GET['posted_for']) && $_GET['posted_for'] == 'Doctor') ? 'selected' : '' ?>>
                                Doctor
                            </option>
                            <option value="Patient"
                                <?= (isset($_GET['posted_for']) && $_GET['posted_for'] == 'Patient') ? 'selected' : '' ?>>
                                Patient
                            </option>
                            <option value="All"
                                <?= (isset($_GET['posted_for']) && $_GET['posted_for'] == 'All') ? 'selected' : '' ?>>
                                All
                            </option>
                        </select>
                    </div>
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
                                <?= (isset($_GET['order']) && $_GET['order'] == 'latest') ? 'selected' : '' ?>>Latest
                                First</option>
                            <option value="oldest"
                                <?= (isset($_GET['order']) && $_GET['order'] == 'oldest') ? 'selected' : '' ?>>Oldest
                                First</option>
                        </select>
                    </div>
                    <button type="submit" id="filter-btn">Apply Filter</button>
                </div>
            </form>

        </section>

        <section class="second-sec">
            <div class="notice-part">
                <?php

            $query = "SELECT * FROM announcement $where_clause $order_by";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $created_at = date('d/m/Y', strtotime($row['created_at']));
                    echo "<article class='notice-box'>
                        <div class='box-top'>
                            <span id='catagory'>{$row['category']}</span>
                            <strong>Posted by: {$row['posted_by']}</strong>
                            <small>{$created_at}</small>
                            <strong>Posted for: {$row['posted_for']}</strong>
                        </div>
                        <div class='box-middle'>
                            <h3>{$row['title']}</h3>
                            <p>{$row['description']}</p>
                        </div>
                        <div class='box-bottom'>
                            <a href='#' id='view'><i class='fa-solid fa-eye' title='View'></i></a>
                            <a href='delete-notice.php?id={$row['announcement_id']}' id='delete'><i class='fa-solid fa-trash' title='Delete'></i></a>
                        </div>
                    </article>";
                }
            } else {
                echo "<p>No notices available.</p>";
            }
            ?>

            </div>
        </section>
    </main>
    <?php include 'admin-footer.php'?>
</body>

</html>