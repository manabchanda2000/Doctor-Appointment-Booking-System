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
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'DESC';

// Initialize conditions array
$conditions = [];

// Handle search
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $conditions[] = "(d.name LIKE '%$search%' 
                      OR d.doctor_id LIKE '%$search%')";
}

// Handle category filter
if (!empty($category)) {
    $category = mysqli_real_escape_string($conn, $category);
    $conditions[] = "ht.category = '$category'";
}

// Build WHERE clause
$where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Handle sorting order
$order = $sort === 'ASC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Health Tips</title>
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

    .tips-part {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
        gap: 10px;
    }

    .tips-box {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        background-color: #fdfdfd;
        box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #BFDBFE;
        width: 100%;
    }

    .box-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        gap: 10px;
    }

    .box-top .doctor {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .box-top img {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        object-fit: fill;
    }

    .box-top a {
        text-decoration: none;
        margin-right: 5px;
    }

    #view {
        color: #2563eb;
    }

    #delete {
        color: #dc2626;
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
    }

    .box-bottom #catagory {
        padding: 5px 10px;
        font-size: 14px;
        background-color: #d1f8d1;
        color: #309700;
        border-radius: 20px;
    }

    .box-bottom #like {
        border: none;
        background-color: transparent;
        color: #2563EB;
        font-size: 16px;
        cursor: pointer;
    }

    .box-bottom small {
        color: #4B5563;
        font-size: 12px;
    }
    </style>
</head>

<body>
    <?php include 'admin-header.php'?>
    <main>
        <section class="first-sec">
            <div class="heading-part">
                <h2>Manage Health Tips</h2>
                <!-- <a href="" id="add-btn">Add Patients</a> -->
            </div>
            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar" placeholder="Search by name, id....."
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <select name="category">
                            <option value="" <?= ($_GET['category'] ?? '') === '' ? 'selected' : '' ?>>All Category
                            </option>
                            <option value="Diet" <?= ($_GET['category'] ?? '') === 'Diet' ? 'selected' : '' ?>>Diet
                            </option>
                            <option value="Skin" <?= ($_GET['category'] ?? '') === 'Skin' ? 'selected' : '' ?>>Skin
                            </option>
                            <option value="Fitness" <?= ($_GET['category'] ?? '') === 'Fitness' ? 'selected' : '' ?>>
                                Fitness</option>
                            <option value="Mental Health"
                                <?= ($_GET['category'] ?? '') === 'Mental Health' ? 'selected' : '' ?>>Mental Health
                            </option>
                            <option value="Lifestyle"
                                <?= ($_GET['category'] ?? '') === 'Lifestyle' ? 'selected' : '' ?>>Lifestyle</option>
                            <option value="Others" <?= ($_GET['category'] ?? '') === 'Others' ? 'selected' : '' ?>>
                                Others</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="sort">
                            <option value="DESC" <?= ($_GET['sort'] ?? 'DESC') === 'DESC' ? 'selected' : '' ?>>Latest
                                First</option>
                            <option value="ASC" <?= ($_GET['sort'] ?? '') === 'ASC' ? 'selected' : '' ?>>Oldest First
                            </option>
                        </select>
                    </div>
                    <button type="submit" id="filter-btn">Apply Filter</button>
                </div>
            </form>

        </section>

        <section class="second-sec">
            <div class="tips-part">
                <?php
               $query = "SELECT ht.*, d.name AS doctor_name, d.profile_img AS doctor_img 
               FROM health_tips ht
               JOIN doctor d ON ht.doctor_id = d.doctor_id
               $where_clause
               ORDER BY ht.created_at $order";
                
                $result = $conn->query($query);
                  if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                          $date = date('F d, Y, h:i A', strtotime($row['created_at']));
                          echo "<article class='tips-box'>
                                  <div class='box-top'>
                                      <span class='doctor'>
                                          <img src='../upload/doctor/" . htmlspecialchars($row['doctor_img'] ?? 'user.jpg') . "' alt='doctor'>
                                          <strong>" . htmlspecialchars($row['doctor_name']) . "</strong>
                                          <span id='id'>Id: " . htmlspecialchars($row['doctor_id']) . "</span>
                                      </span>
                                      <span class='action-btn'>
                                          <a href='../patient/patient-tipsDetails.php?tip_id=" . htmlspecialchars($row['tip_id']) . "' id='view'><i class='fa-solid fa-eye' title='view'></i></a>
                                          <a href='../doctor/doctor-deleteTips.php?tip_id=" . htmlspecialchars($row['tip_id']) . "' id='delete'><i class='fa-solid fa-trash' title='delete'></i></a>
                                      </span>
                                  </div>
                                  <div class='box-middle'>
                                      <h3>" . htmlspecialchars($row['title']) . "</h3>
                                      <p>" . htmlspecialchars(substr($row['description'], 0, 200)) . "...</p>
                                  </div>
                                  <div class='box-bottom'>
                                      <span id='catagory'>" . htmlspecialchars($row['category']) . "</span>
                                      <small>" . $date . "</small>
                                  </div>
                              </article>";
                      }
                  } else {
                      echo "<p>No health tips found.</p>";
                  }
                  ?>
            </div>
        </section>
    </main>
    <?php include 'admin-footer.php'?>
</body>

</html>