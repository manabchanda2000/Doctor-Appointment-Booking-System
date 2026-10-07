<?php
session_start();
include '../connection.php';

// Check if doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header('Location: login.html');
    exit();
}

$doctor_id = $_SESSION['doctor_id'];
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
    <title>Clinic Management</title>
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
    </style>
</head>

<body>
    <?php include 'doctor-header.php'?>
    <main>
        <section class="first-sec">
            <div class="sf-part">
                <form class="search-bar" method="GET" action="">
                    <i class="fas fa-search"></i>
                    <input type="text" id="search-bar" name="search" placeholder="Search by Clinic, Address....."
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button type="submit" id="search-btn">Search</button>
                </form>
                <div class="add">
                    <a href="doctor-addClinic.php?doctor_id=<?= $doctor_id ?>" id="add-btn">Add Clinic</a>
                </div>
            </div>
        </section>

        <section class="second-sec">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Sl no</th>
                            <th>Clinic Name</th>
                            <th>Address</th>
                            <th>Days Available</th>
                            <th>Time Slot</th>
                            <th>Fees</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                    $search = $_GET['search'] ?? '';
                    $like = "%" . $search . "%";

                    $sql = "SELECT * FROM doctor_clinic 
                            WHERE doctor_id = ? 
                            AND (clinic_name LIKE ? OR area LIKE ? OR city LIKE ? OR state LIKE ? OR pincode LIKE ?) ORDER BY created_at DESC";

                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("ssssss", $doctor_id, $like, $like, $like, $like, $like);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $sl = 1;
                            while ($clinic = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $sl++ ?></td>
                            <td><?= htmlspecialchars($clinic['clinic_name']) ?></td>
                            <td>
                                <span><?= htmlspecialchars($clinic['area']) ?>,
                                    <?= htmlspecialchars($clinic['city']) ?></span>
                                <span><?= htmlspecialchars($clinic['state']) ?> -
                                    <?= htmlspecialchars($clinic['pincode']) ?></span>
                            </td>
                            <td><?= htmlspecialchars($clinic['days_available']) ?: 'NA' ?></td>
                            <td><?= date("g:i A", strtotime($clinic['opening_time'])) ?> -
                                <?= date("g:i A", strtotime($clinic['closing_time'])) ?></td>
                            <td><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($clinic['fees']) ?></td>
                            <td>
                                <?= htmlspecialchars($clinic['phone']) ?><br>
                                <?= htmlspecialchars($clinic['email']) ?>
                            </td>
                            <td><?= htmlspecialchars($clinic['status']) ?></td>
                            <td>
                                <a href="clinic-view.php?clinic_id=<?= $clinic['clinic_id'] ?>" id="view"><i
                                        class="fa-solid fa-eye" title="View"></i></a>
                                <a href="clinic-edit.php?clinic_id=<?= $clinic['clinic_id'] ?>" id="approve"><i
                                        class="fa-solid fa-pen-to-square" title="Edit"></i></a>
                                <a href="clinic-delete.php?clinic_id=<?= $clinic['clinic_id'] ?>" id="cancle"
                                    title="Delete" onclick="return confirm('Are you sure to delete?')"><i
                                        class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile;
                        } else {
                            echo '<tr><td colspan="9" style="text-align:center;">No Clinic Found</td></tr>';
                        }
                        $stmt->close();
                    } else {
                        echo '<tr><td colspan="9">Query preparation failed: ' . $conn->error . '</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <?php include 'doctor-footer.php'; ?>
</body>

</html>